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
     *     name="tariffs",
     *     in="query",
     *     required=false,
     *     description="Список ID тарифов аренды (см. /api/rental-tariffs)",
     *     @OA\Schema(
     *       type="array",
     *       @OA\Items(type="integer")
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
     *         "category_car_id", "model_car_id", "color_id", "condition_id", "year_of_issue", "car_number", "count_seat"
     *       },
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
     *       @OA\Property(
     *         property="dop_options",
     *         type="array",
     *         @OA\Items(
     *           type="object",
     *           required={"car_option_id"},
     *           @OA\Property(property="car_option_id", type="integer"),
     *           @OA\Property(property="is_check", type="boolean")
     *         )
     *       ),
     *       @OA\Property(
     *         property="tariffs",
     *         type="array",
     *         description="Список ID тарифов аренды (см. /api/rental-tariffs)",
     *         @OA\Items(type="integer")
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