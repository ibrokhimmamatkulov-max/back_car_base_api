<?php

namespace App\Swagger;

class CarOptionSwagger
{
     /**
     * @OA\Get(
     *     summary="Car dop options",
     *     path="/api/car-settings/dop-options",
     *     tags={"CarSettings/Dop options"},
     *     security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     * @OA\Parameter(
     *     name="filter_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *    @OA\Parameter(
     *     name="filter_id_condition",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     * @OA\Parameter(
     *     name="filter_category_car_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="filter_category_car_id_condition",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     * @OA\Parameter(
     *     name="filter_category_car_name",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="filter_category_car_name_condition",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     * 
     * @OA\Parameter(
     *     name="filter_name",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="filter_name_condition",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *  @OA\Parameter(
     *     name="filter_is_active",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *    @OA\Parameter(
     *     name="filter_is_active_condition",
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
     * 
     *     @OA\Response(response="200", description="Display a listing of type-auto."),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     * )
     */
    public function index(){}

     /**
     * @OA\Post(
     *   path="/api/car-settings/dop-options",
     *   tags={"CarSettings/Dop options"},
     *   summary="Create car dop option",
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
     *     name="name",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="is_active",
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
     * @OA\Get(
     *     summary="Get licensor info by id",
     *     path="/api/car-settings/dop-options/{option_id}/edit",
     *     operationId="/api/car-settings/dop-options/{option_id}/edit",
     *     tags={"CarSettings/Dop options"},
     *     security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="option_id",
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
     *   path="/api/car-settings/dop-options/{option_id}",
     *   tags={"CarSettings/Dop options"},
     *   summary="Update car dop option",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="option_id",
     *     in="path",
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
     *     name="name",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="is_active",
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
    public function update(){}
}