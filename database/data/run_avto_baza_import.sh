#!/bin/sh
# Импорт данных для 12 новых auto_baza таблиц в прод car_base_api.
# Запускать на прод-сервере из директории с docker-compose.yml; рядом с этим
# файлом должен лежать avto_baza_new_tables_import.sql (та же директория,
# либо путь передать первым аргументом).
#
# Использование:
#   DB_ROOT_PASSWORD=*** ./run_avto_baza_import.sh [путь_до_import.sql] [docker-compose-service=mysql] [db=car_base_api]

set -eu

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
IMPORT_FILE="${1:-$SCRIPT_DIR/avto_baza_new_tables_import.sql}"
DB_SERVICE="${2:-mysql}"
DB_NAME="${3:-avto_baza}"

TABLES="body_types car_brands car_conditions car_connecteds car_options category_cars class_cars colors divisions model_cars performer_transports performer_transport_options"

if [ -z "${DB_ROOT_PASSWORD:-}" ]; then
    echo "ERROR: переменная DB_ROOT_PASSWORD не задана." >&2
    exit 1
fi

# Поддержка обоих вариантов CLI: docker compose (v2, плагин) и docker-compose (v1, standalone).
if docker compose version >/dev/null 2>&1; then
    COMPOSE="docker compose"
elif command -v docker-compose >/dev/null 2>&1; then
    COMPOSE="docker-compose"
else
    echo "ERROR: не найден ни 'docker compose', ни 'docker-compose'." >&2
    exit 1
fi

if [ ! -f "$IMPORT_FILE" ]; then
    echo "ERROR: файл с данными не найден: $IMPORT_FILE" >&2
    exit 1
fi

echo "== 1/3: Проверка, что все 12 новых таблиц пустые =="

UNION_QUERY=""
first=1
for t in $TABLES; do
    if [ "$first" = 1 ]; then
        UNION_QUERY="SELECT '$t' AS tbl, COUNT(*) AS cnt FROM \`$t\`"
        first=0
    else
        UNION_QUERY="$UNION_QUERY UNION ALL SELECT '$t', COUNT(*) FROM \`$t\`"
    fi
done

COUNTS_OUTPUT=$($COMPOSE exec -T "$DB_SERVICE" mysql -N -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" -e "$UNION_QUERY")

echo "$COUNTS_OUTPUT"

NONZERO=$(printf '%s\n' "$COUNTS_OUTPUT" | awk '{if ($2+0 != 0) print $1}')

if [ -n "$NONZERO" ]; then
    echo "" >&2
    echo "STOP: следующие таблицы уже не пустые, импорт остановлен:" >&2
    printf '%s\n' "$NONZERO" >&2
    echo "Разберитесь вручную, откуда там данные, прежде чем импортировать." >&2
    exit 1
fi

echo "OK: все 12 таблиц пустые."

echo "== 2/3: Импорт данных из $IMPORT_FILE =="
$COMPOSE exec -T "$DB_SERVICE" mysql -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" < "$IMPORT_FILE"

echo "== 3/3: Счётчики после импорта =="
$COMPOSE exec -T "$DB_SERVICE" mysql -N -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" -e "$UNION_QUERY"

echo ""
echo "Готово. Сверьте счётчики с оригинальным дампом avto_baza.sql и убедитесь,"
echo "что 'users' и другие уже живые таблицы не менялись."
echo "После проверки удалите $IMPORT_FILE и его копии с сервера — там данные легаси-БД."
