<?php

namespace App\Swagger;

class CarBodyTypeSwagger
{
     /**
     * @OA\Get(
     ** path="/api/car-settings/body-types",
     *  tags={"CarSettings/BodyTypes"},
     *  summary="List car body types",
     *  security={{"bearerAuth":{}}},
     *
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
     *  @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     *  @OA\Response(response=401,description="Unauthenticated"),
     *  @OA\Response(response=400,description="Bad Request"),
     *  @OA\Response(response=404,description="not found"),
     *  @OA\Response(response=403,description="Forbidden")
     *)
     **/
    public function index(){}

      /**
     * @OA\Post(
     * path="/api/car-settings/body-types",
     * tags={"CarSettings/BodyTypes"},
     * summary="Create car body types",
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *      @OA\JsonContent(),
     *      @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *            type="object",
     *            required={"category_car_id","name"},
     *            @OA\Property(property="name", type="string"),
     *            @OA\Property(property="category_car_id", type="integer"),
     *            @OA\Property(property="is_active", type="string"),
     *         ),
     *     ),
     * ),
     * @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     * @OA\Response(response=401,description="Unauthenticated"),
     * @OA\Response(response=400,description="Bad Request"),
     * @OA\Response(response=404,description="not found"),
     * @OA\Response(response=403,description="Forbidden")
     *)
     **/
    public function store(){}

     /**
     * @OA\Get(
     ** path="/api/car-settings/body-types/{body_type_id}/edit",
     *  operationId="/api/car-settings/body-types/{body_type_id}/edit",
     *  tags={"CarSettings/BodyTypes"},
     *  summary="Get car body types by id",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *   name="body_type_id",
     *   in="path",
     *   required=true,
     *   @OA\Schema(type="integer"),
     *
     *  ),
     *  @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     *  @OA\Response(response=401,description="Unauthenticated"),
     *  @OA\Response(response=400,description="Bad Request"),
     *  @OA\Response(response=404,description="not found"),
     *  @OA\Response(response=403,description="Forbidden")
     *)
     **/
    public function edit(){}

     /**
     * @OA\Patch(
     * path="/api/car-settings/body-types/{body_type_id}",
     * tags={"CarSettings/BodyTypes"},
     * summary="Update car body types",
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     *   name="body_type_id",
     *   in="path",
     *   required=true,
     *   @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     *   name="category_car_id",
     *   in="query",
     *   required=true,
     *   @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     *   name="name",
     *   in="query",
     *   required=true,
     *   @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     *   name="is_active",
     *   in="query",
     *   required=false,
     *   @OA\Schema(type="string")
     * ),
     * @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     * @OA\Response(response=401,description="Unauthenticated"),
     * @OA\Response(response=400,description="Bad Request"),
     * @OA\Response(response=404,description="not found"),
     * @OA\Response(response=403,description="Forbidden")
     *)
     **/
    public function update(){}
}