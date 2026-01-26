<?php

namespace App\Swagger;

class CarSwagger
{
     /**
     * @OA\Get(
     *     summary="Car List",
     *     path="/api/cars",
     *     tags={"Car"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Лимит",
     *          required=false,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_id",
     *          in="query",
     *          description="Филтр по id",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_id_condition",
     *          in="query",
     *          description="Условия Филтра по id",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_division_id",
     *          in="query",
     *          description="Филтр по Подразделения",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_division_id_condition",
     *          in="query",
     *          description="Условия Филтра по Подразделения",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_color_id",
     *          in="query",
     *          description="Филтр по Цвета авто",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_color_id_condition",
     *          in="query",
     *          description="Условия Филтра по Цвету авто",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_year_of_issue",
     *          in="query",
     *          description="Филтр по Год выпуска",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_year_of_issue_condition",
     *          in="query",
     *          description="Условия Филтра по Год выпуска",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_body_type_id",
     *          in="query",
     *          description="Филтр по Тип кузова",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="filter_body_type_id_condition",
     *          in="query",
     *          description="Условия Филтра по Тип кузова",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *           name="filter_status",
     *           in="query",
     *           description="Филтра по Статусу",
     *           required=false,
     *           @OA\Schema(
     *               type="integer"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_class_id",
     *           in="query",
     *           description="Филтра по Класс авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_class_id_condition",
     *           in="query",
     *           description="Условия Филтр по Класс авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_model_id",
     *           in="query",
     *           description="Филтра по Моделу авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_model_id_condition",
     *           in="query",
     *           description="Условия Филтр по Моделу авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_fuel_type_name",
     *           in="query",
     *           description="Филтра по Тип Топлива",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
    *      @OA\Parameter(
     *           name="filter_fuel_type_name_condition",
     *           in="query",
     *           description="Условия Филтр по Тип Топлива",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_brand_id",
     *           in="query",
     *           description="Филтра по Бренду авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_brand_id_condition",
     *           in="query",
     *           description="Условия Филтр по Бренду авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_option_id",
     *           in="query",
     *           description="Филтра по Доп. параметры",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_option_id_condition",
     *           in="query",
     *           description="Условия Филтра по Доп. параметры",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_carrying_capacity",
     *           in="query",
     *           description="Филтр по Грузоподъемносты авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_carrying_capacity_condition",
     *           in="query",
     *           description="Условия Филтра по Грузоподъемносты авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_length",
     *           in="query",
     *            description="Филтр по Длину авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_length_condition",
     *           in="query",
     *            description="Условия Филтра по Длину авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_height",
     *           in="query",
     *            description="Филтр по Высоту авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
    *      @OA\Parameter(
     *           name="filter_tarif_id",
     *           in="query",
     *            description="Филтр по таьриф id",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_height_condition",
     *           in="query",
     *            description="Условия Филтра по Высоту авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_width",
     *           in="query",
     *            description="Филтр по Ширины авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_width_condition",
     *           in="query",
     *           description="Условия Филтра по Ширины авто",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
      *      @OA\Parameter(
     *           name="filter_car_number",
     *           in="query",
     *            description="Филтр по Гос.номеру авто.",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_car_number_condition",
     *           in="query",
     *           description="Условия для Филтра гос.номер авто.",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_from_created_at",
     *           in="query",
     *           description="Филтр по Дата приема от",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_from_created_at_condition",
     *           in="query",
     *           description="Условия Филтра по Дата приема от",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_to_created_at",
     *           in="query",
     *            description="Филтр по Дата приема до",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_to_created_at_condition",
     *           in="query",
     *           description="Условия Филтра по Дата приема до",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     * 
     * 
     *     @OA\Parameter(
     *          name="filter_city_id",
     *          in="query",
     *          description="Фильтр по городу",
     *          required=false,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          name="filter_gearbox_id",
     *          in="query",
     *          description="Фильтр по коробке передач",
     *          required=false,
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          name="filter_min_rent_days",
     *          in="query",
     *          description="Фильтр по минимальному сроку аренды",
     *          required=false,
     *          @OA\Schema(type="integer")
     *     ),
     * 
     * 
     *      @OA\Parameter(
     *           name="filter_count_seat",
     *           in="query",
     *           description="Филтр по Количество мест",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="filter_count_seat_condition",
     *           in="query",
     *           description="Условия Филтра по Количество мест",
     *           required=false,
     *           @OA\Schema(
     *               type="string"
     *           )
     *      ),
           *     @OA\Parameter(
     *         name="filter_car_park_id",
     *         in="query",
     *         required=false,
     *           @OA\Schema(
     *             type="string"
     *            )
     *     ),
         *     @OA\Parameter(
     *         name="filter_promo_code_condition",
     *         in="query",
     *         required=false,
     *           @OA\Schema(
     *             type="string"
     *            )
     *     ),
     *   @OA\Parameter(
     *     in="query", 
     *     name="filter_journal_car",
     *     description="Фильтр журнал автомобилей",
     *     required=false, 
     *     schema={
     *          "type": "string", 
     *          "enum": {"ALL_LIST",
     *                  "ACTIVE",
     *                  "DISMISSED",
     *                  "CREATED_BY_ME",
     *                  "INSPECTION_IN_THE_OFFICE",
     *                  "DOUBLES"},     
     *          "default" : "ALL_LIST",
     *      }
     *   ),
     *      
     *     @OA\Response(response="200", description="Display a listing of type-auto."),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     * )
     */

    public function index(){}


    /**
     * @OA\Post(
     ** path="/api/cars",
     *   tags={"Car"},
     *   summary="Create car",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="division_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="category_car_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="model_car_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="body_type_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="color_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="condition_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="year_of_issue",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="car_number",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="count_seat",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="city_id",
     *     in="query",
     *     required=true,
     *     description="Город",
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="address",
     *     in="query",
     *     required=true,
     *     description="Адрес автомобиля",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="gearbox_id",
     *     in="query",
     *     required=true,
     *     description="Коробка передач",
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="min_rent_days",
     *     in="query",
     *     required=true,
     *     description="Минимальный срок аренды (дни)",
     *     @OA\Schema(type="integer")
     *   ),
     *    @OA\Parameter(
     *       name="fuel_type_id",
     *       in="query",
     *       required=true,
     *       @OA\Schema(
     *            type="integer"
     *       )
     *     ),
     *   @OA\Parameter(
     *     name="cargo_properties",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="dop_info",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="dop_options",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
    *   @OA\Parameter(
     *     name="car_park_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *
     *     @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     *     @OA\Response(response=401,description="Unauthenticated"),
     *     @OA\Response(response=400,description="Bad Request"),
     *     @OA\Response(response=404,description="not found"),
     *     @OA\Response(response=403,description="Forbidden")
     *)
     **/

     public function store(){}

       /**
     * @OA\Patch(
     *   path="/api/cars/{car_id}",
     *   tags={"Car"},
     *   summary="Update car",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="car_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={
     *         "division_id", "category_car_id", "model_car_id", "color_id", "condition_id", "year_of_issue", "car_number", "count_seat"
     *       },
     *       @OA\Property(property="division_id", type="integer"),
     *       @OA\Property(property="category_car_id", type="integer"),
     *       @OA\Property(property="model_car_id", type="integer"),
     *       @OA\Property(property="body_type_id", type="integer"),
     *       @OA\Property(property="color_id", type="integer"),
     *       @OA\Property(property="condition_id", type="integer"),
     *       @OA\Property(property="fuel_type_id", type="integer"),
     *       @OA\Property(property="year_of_issue", type="integer"),
     *       @OA\Property(property="car_number", type="string"),
     *       @OA\Property(property="city_id", type="integer"),
     *       @OA\Property(property="address", type="string"),
     *       @OA\Property(property="gearbox_id", type="integer"),
     *       @OA\Property(property="min_rent_days", type="integer"),
     *       @OA\Property(property="count_seat", type="integer"),
     *       @OA\Property(property="cargo_properties", type="string"),
     *       @OA\Property(property="dop_info", type="string"),
     *       @OA\Property(property="car_park_id", type="integer"),
     *       @OA\Property(
     *         property="dop_options",
     *         type="array",
     *         @OA\Items(
     *           type="object",
     *           required={"car_option_id"},
     *           @OA\Property(property="car_option_id", type="integer"),
     *           @OA\Property(property="is_check", type="boolean")
     *         )
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=401, description="Unauthenticated"),
     *   @OA\Response(response=400, description="Bad Request"),
     *   @OA\Response(response=404, description="Not Found"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */

     public function update(){}

    /**
     * @OA\Get(
     *     summary="Get car info by id",
     *     path="/api/cars/{car_id}/show",
     *     tags={"Car"},
     *     security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="car_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema
     *          (type="integer")
     *     ),
     *     @OA\Response(response="200", description="Display a listing of clients."),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */

     public function edit(){}

    /**
     * @OA\Get(
     *     summary="Car fuel type list",
     *     path="/api/cars/fuel-types",
     *     tags={"Car"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     *     @OA\Response(response=401,description="Unauthenticated"),
     *     @OA\Response(response=400,description="Bad Request"),
     *     @OA\Response(response=404,description="not found"),
     *     @OA\Response(response=403,description="Forbidden")
     * )
     */

     public function fuel_types(){}

    /**
     * @OA\Get(
     *     summary="Get car dop options",
     *     path="/api/cars/dop-options",
     *     tags={"Car"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *      name="category_car_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     *     @OA\Response(response=401,description="Unauthenticated"),
     *     @OA\Response(response=400,description="Bad Request"),
     *     @OA\Response(response=404,description="not found"),
     *     @OA\Response(response=403,description="Forbidden")
     * )
     */

     public function car_dop_options(){}


}