<?php

namespace App\Swagger;

class CitySwagger
{
    /**
     * @OA\Get(
     *     path="/api/cities",
     *     tags={"Cities"},
     *     summary="Список городов",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/cities",
     *     tags={"Cities"},
     *     summary="Создать город",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название города",
     *         @OA\Schema(type="string", example="Душанбе")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         description="Описание",
     *         @OA\Schema(type="string", example="Столица")
     *     ),
     *
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(){}

    /**
     * @OA\Get(
     *     path="/api/cities/{id}",
     *     tags={"Cities"},
     *     summary="Получить город",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID города",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function show(){}

    /**
     * @OA\Put(
     *     path="/api/cities/{id}",
     *     tags={"Cities"},
     *     summary="Обновить город",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID города",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="Название города",
     *         @OA\Schema(type="string", example="Хуҷанд")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         description="Описание",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(response=200, description="Updated")
     * )
     */
    public function update(){}

    /**
     * @OA\Delete(
     *     path="/api/cities/{id}",
     *     tags={"Cities"},
     *     summary="Удалить город",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID города",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Deleted")
     * )
     */
    public function destroy(){}
}