<?php

namespace App\Swagger;

class RentalTariffSwagger
{
    /**
     * @OA\Get(
     *     path="/api/rental-tariffs",
     *     tags={"Rental Tariffs"},
     *     summary="Список тарифов",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/rental-tariffs",
     *     tags={"Rental Tariffs"},
     *     summary="Создать тариф",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="performer_transport_id",
     *         in="query",
     *         required=true,
     *         description="ID автомобиля",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Parameter(
     *         name="duration_days",
     *         in="query",
     *         required=true,
     *         description="Количество дней",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         required=true,
     *         description="Цена",
     *         @OA\Schema(type="number", example=500000)
     *     ),
     *     @OA\Parameter(
     *         name="free_weekend_day",
     *         in="query",
     *         required=false,
     *         description="Бесплатный выходной",
     *         @OA\Schema(type="integer", example=0)
     *     ),
     *
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(){}

    /**
     * @OA\Get(
     *     path="/api/rental-tariffs/{id}",
     *     tags={"Rental Tariffs"},
     *     summary="Получить тариф",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID тарифа",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function show(){}

    /**
     * @OA\Put(
     *     path="/api/rental-tariffs/{id}",
     *     tags={"Rental Tariffs"},
     *     summary="Обновить тариф",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID тарифа",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="duration_days",
     *         in="query",
     *         required=true,
     *         description="Количество дней",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         required=true,
     *         description="Цена",
     *         @OA\Schema(type="number", example=700000)
     *     ),
     *     @OA\Parameter(
     *         name="free_weekend_day",
     *         in="query",
     *         required=false,
     *         description="Бесплатный выходной",
     *         @OA\Schema(type="integer", example=0)
     *     ),
     *
     *     @OA\Response(response=200, description="Updated")
     * )
     */
    public function update(){}

    /**
     * @OA\Delete(
     *     path="/api/rental-tariffs/{id}",
     *     tags={"Rental Tariffs"},
     *     summary="Удалить тариф",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID тарифа",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Deleted")
     * )
     */
    public function destroy(){}
}