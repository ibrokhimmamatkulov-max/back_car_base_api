<?php

namespace App\Swagger;

class RentalSwagger
{
    /**
     * @OA\Get(
     *     path="/api/rentals",
     *     tags={"Rentals"},
     *     summary="Список аренд",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/rentals",
     *     tags={"Rentals"},
     *     summary="Создать аренду",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="performer_transport_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="performer_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="start_datetime", in="query", required=true, @OA\Schema(type="string", example="2026-01-20")),
     *     @OA\Parameter(name="end_datetime", in="query", required=true, @OA\Schema(type="string", example="2026-01-27")),
     *
     *     @OA\Response(response=201, description="Created")
     * )
     */
    public function store(){}

    /**
     * @OA\Get(
     *     path="/api/rentals/{id}",
     *     tags={"Rentals"},
     *     summary="Получить аренду",
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
     *     path="/api/rentals/{id}",
     *     tags={"Rentals"},
     *     summary="Обновить аренду",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="end_datetime", in="query", required=false, @OA\Schema(type="string", example="2026-01-27")),
     *
     *     @OA\Response(response=200, description="Updated")
     * )
     */
    public function update(){}

    /**
     * @OA\Delete(
     *     path="/api/rentals/{id}",
     *     tags={"Rentals"},
     *     summary="Удалить аренду",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Deleted")
     * )
     */
    public function destroy(){}
}