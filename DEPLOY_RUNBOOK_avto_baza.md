# Runbook: деплой merge'а auto_baza в прод (car_base_api)

Контекст: локально уже сделано и проверено — 12 новых миграций для таблиц
`category_cars, car_brands, class_cars, body_types, car_conditions,
car_connecteds, colors, car_options, model_cars, performer_transports,
performer_transport_options, divisions`, плюс правки в моделях/контроллерах/
`config/database.php`, убирающие отдельное подключение `auto_baza` в пользу
дефолтного `mysql`. Прод работает на этом же `docker-compose.yml`.

## 0. Главное правило: НЕ импортировать данные вслепую

Часть таблиц из `avto_baza.sql` **уже существует на проде через старые
миграции** и наверняка содержит текущие боевые данные:

- `application_statuses`, `cities`, `gearboxes`, `performer_transport_photos`,
  `rental_applications`, `rental_statuses`, `rental_tariffs`, `rentals`,
  `role_user`, `roles`, `users`

Для них дамп `avto_baza.sql` — это **снимок другого (легаси) окружения**, а не
источник истины для прода. Импортировать эти таблицы с проде **нельзя** —
будет либо конфликт по PRIMARY KEY (id пересекаются), либо затирание
актуальных данных значениями двухмесячной давности.

Импортировать на проде нужно данные **только для 12 новых таблиц**, которых
там ещё физически нет:

```
body_types, car_brands, car_conditions, car_connecteds, car_options,
category_cars, class_cars, colors, divisions, model_cars,
performer_transports, performer_transport_options
```

Перед импортом на каждую из этих 12 таблиц — обязательная проверка, что она
пустая (см. шаг 5). Если не пустая — значит кто-то уже данные туда положил,
и импорт нужно остановить и разобраться, а не перезаписывать.

## 1. Перед деплоем — исправить сопутствующий баг (не мой, преднайденный)

`config/permission.php` → `table_names.roles = 'roles'` конфликтует с именем
таблицы вашей кастомной модели `Role` (`create_roles_table`). Из-за этого
`2026_03_13_053550_create_permission_tables` падает при миграции. Пакет
Spatie Permission нигде в коде не используется (`assignRole`/`hasRole` не
встречаются) — это мёртвая зависимость, ломающая чистый deploy.

Фикс (безопасный, ничего не задействует):

```php
// config/permission.php
'roles' => 'permission_roles', // было 'roles'
```

Важно: `docker-entrypoint.sh` запускает `php artisan migrate --force` с
`set -e`, а контейнер `unless-stopped`. Если миграция упадёт на проде так же,
как упала локально без этого фикса — контейнер `app` уйдёт в
restart-loop (кратковременный даунтайм на каждый рестарт), пока это не
починится само по счастливой случайности (как было у меня локально) либо не
будет исправлено. **Поэтому фикс должен попасть в деплой вместе с остальными
изменениями, а не после.**

## 2. Закоммитить и смёрджить изменения

Сейчас всё лежит как uncommitted изменения на ветке `feat/landing-endpoints`:

```bash
git status --short   # свериться со списком: 12 новых миграций + правки моделей/конфига
git add database/migrations/2026_01_22_10*.php \
        config/database.php config/permission.php \
        app/Models app/Http/Controllers/Api
git commit -m "Merge auto_baza legacy tables into default mysql connection"
git push origin feat/landing-endpoints
```

- `avto_baza.sql` **не коммитить** — это сырой дамп с реальными телефонами и
  хэшами паролей пользователей. Он и так untracked, просто не добавляйте его
  (`git add -A` в этой ветке не используйте).
- Смёрджите ветку в `main` (или в ту ветку, с которой у вас настроен деплой)
  через обычный PR-процесс.

## 3. Бэкап прод-БД (обязательно, до чего-либо ещё)

На проде, до деплоя нового кода:

```bash
docker compose exec mysql mysqldump -u root -p"$DB_ROOT_PASSWORD" \
  --single-transaction car_base_api > backup_car_base_api_$(date +%Y%m%d_%H%M).sql
```

Скопируйте этот файл за пределы контейнера/сервера (например, `scp` себе или
в S3/хранилище бэкапов). Это ваш путь отката, если что-то пойдёт не так.

## 4. Деплой кода и миграция схемы

```bash
git pull origin main            # или merge ветки, с которой деплоите
docker compose up -d --build app nginx
```

`docker-entrypoint.sh` сам прогонит `php artisan migrate --force` при старте
контейнера `app`. Смотрите логи в реальном времени:

```bash
docker compose logs -f app
```

Ожидаемо: все миграции, включая 12 новых и (уже исправленную)
`create_permission_tables`, проходят DONE без FAIL. Если увидите FAIL —
**останавливайтесь и не переходите к шагу 5**, сначала разберитесь (скорее
всего типовая проблема — конфликт имён таблиц/колонок с чем-то, что уже есть
на проде и чего не было локально).

## 5. Импорт данных — только для 12 новых таблиц

Сначала проверка, что на проде эти таблицы реально пустые:

```bash
docker compose exec mysql mysql -u root -p"$DB_ROOT_PASSWORD" -e "
USE car_base_api;
SELECT 'body_types', COUNT(*) FROM body_types
UNION ALL SELECT 'car_brands', COUNT(*) FROM car_brands
UNION ALL SELECT 'car_conditions', COUNT(*) FROM car_conditions
UNION ALL SELECT 'car_connecteds', COUNT(*) FROM car_connecteds
UNION ALL SELECT 'car_options', COUNT(*) FROM car_options
UNION ALL SELECT 'category_cars', COUNT(*) FROM category_cars
UNION ALL SELECT 'class_cars', COUNT(*) FROM class_cars
UNION ALL SELECT 'colors', COUNT(*) FROM colors
UNION ALL SELECT 'divisions', COUNT(*) FROM divisions
UNION ALL SELECT 'model_cars', COUNT(*) FROM model_cars
UNION ALL SELECT 'performer_transports', COUNT(*) FROM performer_transports
UNION ALL SELECT 'performer_transport_options', COUNT(*) FROM performer_transport_options;
"
```

Все счётчики должны быть `0`. Если нет — стоп, не импортируйте, разбирайтесь
отдельно.

Дальше на проде соберите тот же отфильтрованный SQL, что использовался
локально, но **только по этим 12 таблицам** (НЕ включайте
application_statuses/cities/gearboxes/performer_transport_photos/
rental_*/role_user/roles/users — они уже живые на проде):

```bash
grep -E "^INSERT INTO \`(body_types|car_brands|car_conditions|car_connecteds|car_options|category_cars|class_cars|colors|divisions|model_cars|performer_transports|performer_transport_options)\` VALUES" \
  avto_baza.sql > /tmp/avto_baza_new_tables_only.sql

{
  echo "SET NAMES utf8mb4;"
  echo "SET FOREIGN_KEY_CHECKS=0;"
  cat /tmp/avto_baza_new_tables_only.sql
  echo "SET FOREIGN_KEY_CHECKS=1;"
} > /tmp/import_final.sql

docker compose exec -T mysql mysql -u root -p"$DB_ROOT_PASSWORD" car_base_api < /tmp/import_final.sql
```

`avto_baza.sql` нужно будет предварительно занести на прод-сервер (например,
`scp` из этой машины) — в git его нет и не должно быть.

Если у вас есть основания думать, что реальные данные в auto_baza с
2026-05-04 (дата дампа) успели измениться — сначала снимите свежий дамп с
исходной auto_baza-системы тем же способом, каким был получен
`avto_baza.sql`, и используйте его вместо старого файла.

## 6. Проверка после деплоя

```bash
docker compose exec -e XDG_CONFIG_HOME=/tmp app php artisan tinker --execute="
echo 'CarBrand: ' . \App\Models\CarBrand::count() . PHP_EOL;
echo 'PerformerTransport: ' . \App\Models\PerformerTransport::count() . PHP_EOL;
echo 'Users (не должно было измениться!): ' . \App\Models\User::count() . PHP_EOL;
"
```

- Счётчики новых таблиц — как в дампе (`car_brands` 311, `model_cars` 3916 и
  т.д., см. предыдущий разговор).
- Счётчик `users` (и других "уже живых" таблиц) — **должен остаться таким же,
  каким был до деплоя**, если изменился — это сигнал, что что-то было
  перезаписано, нужно поднимать бэкап из шага 3.
- Прогнать несколько ключевых ручек API (car-brands, performer-transports,
  rentals) и глазами свериться, что данные адекватные.

## 7. Откат при проблемах

```bash
docker compose exec -T mysql mysql -u root -p"$DB_ROOT_PASSWORD" car_base_api < backup_car_base_api_ВРЕМЯ.sql
git revert <commit>   # или откат на предыдущий тег/коммит + docker compose up -d --build
```

## 8. Уборка

- Удалите временные файлы (`/tmp/import_final.sql`, копию `avto_baza.sql` на
  сервере) после успешной проверки — там реальные телефоны и хэши паролей.
- Локально `avto_baza.sql` тоже не тащите в git.
