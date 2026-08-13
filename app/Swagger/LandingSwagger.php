<?php

namespace App\Swagger;

class LandingSwagger
{
    /**
     * @OA\Get(
     *     path="/api/landing/cities",
     *     tags={"Landing"},
     *     summary="Список городов для каталога",
     *     description="Возвращает активные города из таблицы polygons (mysql_location). Без авторизации.",
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="OK"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id",   type="integer", example=1),
     *                     @OA\Property(property="name", type="string",  example="Душанбе"),
     *                     @OA\Property(property="lat",  type="number",  example=38.559772),
     *                     @OA\Property(property="lng",  type="number",  example=68.773716)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function cities() {}

    /**
     * @OA\Get(
     *     path="/api/landing/rental-tariffs",
     *     tags={"Landing"},
     *     summary="Список тарифов для landing",
     *     description="Возвращает список тарифов аренды без авторизации.",
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function rentalTariffs() {}

    /**
     * @OA\Get(
     *     path="/api/landing/gearboxes",
     *     tags={"Landing"},
     *     summary="Список коробок передач для landing",
     *     description="Возвращает список коробок передач без авторизации.",
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function gearboxes() {}

    /**
     * @OA\Get(
     *     path="/api/landing/fuel-types",
     *     tags={"Landing"},
     *     summary="Список типов топлива для landing",
     *     description="Возвращает активные типы топлива без авторизации.",
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function fuelTypes() {}

    /**
     * @OA\Get(
     *     path="/api/landing/offers",
     *     tags={"Landing"},
     *     summary="Список объявлений с фильтрами",
     *     description="Возвращает постраничный список активных объявлений. Без авторизации.",
     *
     *     @OA\Parameter(name="city_id",       in="query", required=false, description="ID города",             @OA\Schema(type="integer")),
     *     @OA\Parameter(name="gearbox_id",    in="query", required=false, description="ID типа коробки передач", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="fuel_type_id",  in="query", required=false, description="ID типа топлива", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="tariff_id",     in="query", required=false, description="ID тарифа", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="duration_days", in="query", required=false, description="Длительность тарифа (дни): 1, 7, 30...", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sort",          in="query", required=false, description="Сортировка: price_asc | price_desc | year_asc | year_desc", @OA\Schema(type="string", enum={"price_asc","price_desc","year_asc","year_desc"})),
     *     @OA\Parameter(name="per_page",      in="query", required=false, description="Записей на страницу (по умолчанию 12)", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="page",          in="query", required=false, description="Номер страницы", @OA\Schema(type="integer")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code",    type="integer", example=200),
     *             @OA\Property(property="message", type="string",  example="OK"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id",            type="integer", example=1),
     *                         @OA\Property(property="brand",         type="string",  example="Toyota"),
     *                         @OA\Property(property="model",         type="string",  example="Camry"),
     *                         @OA\Property(property="year",          type="integer", example=2022),
     *                         @OA\Property(property="min_price",     type="number",  example=150000),
     *                         @OA\Property(property="min_rent_days", type="integer", example=3),
     *                         @OA\Property(property="city",    type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string", example="Душанбе")),
     *                         @OA\Property(property="gearbox", type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string", example="Автомат")),
     *                         @OA\Property(property="body_type", type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string", example="Седан")),
     *                         @OA\Property(property="photos", type="array", @OA\Items(type="string", example="/storage/photos/car.jpg"))
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="meta",
     *                     type="object",
     *                     @OA\Property(property="total",        type="integer", example=45),
     *                     @OA\Property(property="per_page",     type="integer", example=12),
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="last_page",    type="integer", example=4)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function offers() {}

    /**
     * @OA\Get(
     *     path="/api/landing/offers/{id}",
     *     tags={"Landing"},
     *     summary="Детальная страница объявления",
     *     description="Возвращает полную информацию об активном объявлении: фото, тарифы, опции, характеристики. Без авторизации.",
     *
     *     @OA\Parameter(name="id", in="path", required=true, description="ID объявления", @OA\Schema(type="integer")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code",    type="integer", example=200),
     *             @OA\Property(property="message", type="string",  example="OK"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id",            type="integer", example=1),
     *                 @OA\Property(property="brand",         type="string",  example="Toyota"),
     *                 @OA\Property(property="model",         type="string",  example="Camry"),
     *                 @OA\Property(property="year",          type="integer", example=2022),
     *                 @OA\Property(property="count_seat",    type="integer", example=5),
     *                 @OA\Property(property="dop_info",      type="string",  nullable=true),
     *                 @OA\Property(property="address",       type="string",  nullable=true),
     *                 @OA\Property(property="min_rent_days", type="integer", example=3),
     *                 @OA\Property(property="performer_id",  type="integer", example=42),
     *                 @OA\Property(property="city",      type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string")),
     *                 @OA\Property(property="gearbox",   type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string")),
     *                 @OA\Property(property="body_type", type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string")),
     *                 @OA\Property(property="color",     type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string")),
     *                 @OA\Property(property="fuel_type", type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string")),
     *                 @OA\Property(property="photos",      type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="dop_options", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string")
     *                 )),
     *                 @OA\Property(property="tariffs", type="array", @OA\Items(
     *                     @OA\Property(property="id",               type="integer", example=1),
     *                     @OA\Property(property="duration_days",    type="integer", example=7),
     *                     @OA\Property(property="price",            type="number",  example=180000),
     *                     @OA\Property(property="free_weekend_day", type="integer", example=1)
     *                 ))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Объявление не найдено",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code",    type="integer", example=404),
     *             @OA\Property(property="message", type="string",  example="Объявление не найдено.")
     *         )
     *     )
     * )
     */
    public function offer() {}

    /**
     * @OA\Post(
     *     path="/api/landing/apply",
     *     tags={"Landing"},
     *     summary="Отправить заявку с лендинга",
     *     description="Принимает заявку из формы. Лимит: 10 запросов в минуту с одного IP. Без авторизации.",
     *
     *     @OA\Parameter(name="name",      in="query", required=true,  description="Имя клиента",           @OA\Schema(type="string",  maxLength=255)),
     *     @OA\Parameter(name="phone",     in="query", required=true,  description="Телефон (формат: 992XXXXXXXXX, +992XXXXXXXXX или 9 цифр)", @OA\Schema(type="string")),
     *     @OA\Parameter(name="city_id",   in="query", required=true,  description="ID города",             @OA\Schema(type="integer")),
     *     @OA\Parameter(name="offer_id",  in="query", required=true,  description="ID объявления (performer_transport_id)", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="tariff_id", in="query", required=false, description="ID тарифа (rental_tariff_id). Должен принадлежать выбранному объявлению.", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="comment",   in="query", required=false, description="Комментарий",           @OA\Schema(type="string",  maxLength=1000)),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Заявка принята",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code",    type="integer", example=201),
     *             @OA\Property(property="message", type="string",  example="Заявка принята. Мы свяжемся с вами в ближайшее время."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="application_id", type="integer", example=15)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code",    type="integer", example=422),
     *             @OA\Property(property="message", type="string",  example="Ошибка валидации."),
     *             @OA\Property(property="errors",  type="object",
     *                 @OA\Property(property="phone", type="array", @OA\Items(type="string", example="Некорректный номер телефона."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Слишком много запросов",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
     */
    public function apply() {}
}
