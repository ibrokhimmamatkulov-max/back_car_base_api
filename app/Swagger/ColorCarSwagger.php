<?php

namespace App\Swagger;

class ColorCarSwagger
{
     /**
     * @OA\Get(
     *     summary="Car colors",
     *     path="/api/car-settings/car-colors",
     *     tags={"CarSettings/CarColors"},
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
     *     name="filter_name_tj",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="filter_name_tj_condition",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="string"
     *      )
     *      ),
     *   @OA\Parameter(
     *     name="filter_name_for_sms",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="filter_name_for_sms_condition",
     *     in="query",
     *     required=false,
     *     @OA\Schema(
     *          type="string"
     *      )
     *      ),
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
     *   path="/api/car-settings/car-colors",
     *   tags={"CarSettings/CarColors"},
     *   summary="Create car colors",
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
     *     name="name_tj",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="name_for_sms",
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
     *     summary="Get car color by id",
     *     path="/api/car-settings/car-colors/{color_id}/edit",
     *     operationId="/api/car-settings/car-colors/{color_id}/edit",
     *     tags={"CarSettings/CarColors"},
     *     security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="color_id",
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
     *   path="/api/car-settings/car-colors/{color_id}",
     *   tags={"CarSettings/CarColors"},
     *   summary="Update car color",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="color_id",
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
     *     name="name_tj",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *          type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="name_for_sms",
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