<?php

namespace App\Swagger;

class PerformerTransportPhotoSwagger
{
      /**
     * @OA\Get(
     *     path="/api/car-photos/{car_id}",
     *     tags={"Car Photos"},
     *     summary="Список фотографий автомобиля",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="car_id",
     *         in="path",
     *         required=true,
     *         description="ID автомобиля",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/car-photos",
     *     tags={"Car Photos"},
     *     summary="Загрузить фото автомобиля",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"performer_transport_id", "photo"},
     *                 @OA\Property(
     *                     property="performer_transport_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="photo",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Created"
     *     )
     * )
     */

    public function store(){}

    /**
     * @OA\Delete(
     *     path="/api/car-photos/{id}",
     *     tags={"Car Photos"},
     *     summary="Удалить фото",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID фотографии",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Deleted")
     * )
     */

    public function destroy(){}
}