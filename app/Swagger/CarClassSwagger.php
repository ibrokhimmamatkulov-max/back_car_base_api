<?php

namespace App\Swagger;

class CarClassSwagger
{
     /**
     * @OA\Get(
     *     summary="Car class list",
     *     path="/api/car-settings/classes",
     *     tags={"CarSettings/Classes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Display a listing of type-auto."),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not Found"),
     * )
     */
    public function index(){}

     /**
     * @OA\Post(
     ** path="/api/car-settings/classes",
     *   tags={"CarSettings/Classes"},
     *   summary="Create car class",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="name",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
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
     *     summary="Get car brand by id",
     *     path="/api/car-settings/classes/{class_car_id}/edit",
     *     operationId="/api/car-settings/classes/{class_car_id}/edit",
     *     tags={"CarSettings/Classes"},
     *     security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="class_car_id",
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
     ** path="/api/car-settings/classes/{class_car_id}",
     *   tags={"CarSettings/Classes"},
     *   summary="Update car class",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="class_car_id",
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