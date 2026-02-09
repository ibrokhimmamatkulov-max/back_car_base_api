<?php

namespace App\Swagger;

class CarModelSwagger
{
     /**
     * @OA\Post(
     *     summary="Car model data list",
     *     path="/api/car-settings/model-cars/data",
     *     tags={"CarSettings/ModelCars"},
     *     security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="category_car_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *     @OA\Response(response="200", description="Display a listing of type-auto."),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     * )
     */
    public function data(){}

     /**
     * @OA\Get(
     *     summary="Car model list",
     *     path="/api/car-settings/model-cars",
     *     tags={"CarSettings/ModelCars"},
     *     security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_id_condition",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *      @OA\Parameter(
     *          name="filter_name",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="filter_name_condition",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *   @OA\Parameter(
     *     name="filter_category_car_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_car_brand_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_class_car_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_car_seat_from",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_car_seat_before",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_from_created_at",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="date-time")
     *   ),
     *   @OA\Parameter(
     *     name="filter_to_created_at",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="date-time")
     *   ),
     *   @OA\Parameter(
     *     name="filter_is_active",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
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
     ** path="/api/car-settings/model-cars",
     *   tags={"CarSettings/ModelCars"},
     *   summary="Create model car",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="name",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *  @OA\Parameter(
     *      name="car_model",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *    ),
     *   @OA\Parameter(
     *     name="car_seat_from",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="car_seat_before",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="car_brand_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="class_car_id",
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
     *     name="is_active",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="string"
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
     * @OA\Get(
     *     summary="Get model car by id",
     *     path="/api/car-settings/model-cars/{car_model_id}/edit",
     *     operationId="/api/car-settings/model-cars/{car_model_id}/edit",
     *     tags={"CarSettings/ModelCars"},
     *     security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="car_model_id",
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
     * @OA\Patch(
     ** path="/api/car-settings/model-cars/{car_model_id}",
     *   tags={"CarSettings/ModelCars"},
     *   summary="Update model car",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="car_model_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="name",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *      name="car_model",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *    ),
     *   @OA\Parameter(
     *     name="car_seat_from",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="car_seat_before",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="car_brand_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="class_car_id",
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
     *     name="is_active",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="string"
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
    public function update(){}



}