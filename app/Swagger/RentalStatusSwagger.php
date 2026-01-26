<?php

namespace App\Swagger;

class RentalStatusSwagger
{
     /**
     * @OA\Get(
     *     path="/api/rental-statuses",
     *     tags={"Rental Statuses"},
     *     summary="Список статусов аренды",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */

    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/rental-statuses",
     *     tags={"Rental Statuses"},
     *     summary="Создать статус аренды",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=true,
     *         description="Код статуса (active, overdue, waiting_return)",
     *         @OA\Schema(type="string", example="active")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название статуса",
     *         @OA\Schema(type="string", example="Активна")
     *     ),
     *
     *     @OA\Response(response=201, description="Created")
     * )
     */

    public function store(){}

    /**
     * @OA\Get(
     *     path="/api/rental-statuses/{id}",
     *     tags={"Rental Statuses"},
     *     summary="Получить статус аренды",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID статуса",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */

    public function show(){}

    /**
     * @OA\Put(
     *     path="/api/rental-statuses/{id}",
     *     tags={"Rental Statuses"},
     *     summary="Обновить статус аренды",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID статуса",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=true,
     *         description="Код статуса",
     *         @OA\Schema(type="string", example="overdue")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название статуса",
     *         @OA\Schema(type="string", example="Просрочена")
     *     ),
     *
     *     @OA\Response(response=200, description="Updated")
     * )
     */

    public function update(){}

    /**
     * @OA\Delete(
     *     path="/api/rental-statuses/{id}",
     *     tags={"Rental Statuses"},
     *     summary="Удалить статус аренды",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID статуса",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Deleted")
     * )
     */

    public function destroy(){}
}