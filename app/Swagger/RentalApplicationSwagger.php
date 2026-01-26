<?php

namespace App\Swagger;

class RentalApplicationSwagger
{
     /**
     * @OA\Get(
     *     path="/api/rental-applications",
     *     tags={"Rental Applications"},
     *     summary="Список заявок",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/rental-applications",
     *     tags={"Rental Applications"},
     *     summary="Создать заявку",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="performer_transport_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="rental_tariff_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="user_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="phone", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(name="city_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="promo_code", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="status_id", in="query", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=201, description="Created")
     * )
     */

    public function store(){}

    /**
     * @OA\Get(
     *     path="/api/rental-applications/{id}",
     *     tags={"Rental Applications"},
     *     summary="Получить заявку",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */

    public function show(){}

    /**
     * @OA\Put(
     *     path="/api/rental-applications/{id}",
     *     tags={"Rental Applications"},
     *     summary="Обновить заявку",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="promo_code", in="query", required=false, @OA\Schema(type="string")),
     *
     *     @OA\Response(response=200, description="Updated")
     * )
     */

    public function update(){}

    /**
     * @OA\Delete(
     *     path="/api/rental-applications/{id}",
     *     tags={"Rental Applications"},
     *     summary="Удалить заявку",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Deleted")
     * )
     */

    public function destroy(){}
}