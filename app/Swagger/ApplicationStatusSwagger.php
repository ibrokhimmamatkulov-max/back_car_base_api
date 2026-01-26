<?php

namespace App\Swagger;

class ApplicationStatusSwagger
{
    /**
     * @OA\Get(
     *     path="/api/application-statuses",
     *     tags={"Application Statuses"},
     *     summary="Список статусов заявок",
     *     @OA\Response(response=200, description="OK")
     * )
     */

    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/application-statuses",
     *     tags={"Application Statuses"},
     *     summary="Создать статус заявки",
     *
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=true,
     *         description="Код статуса",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название статуса",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(response=201, description="Created")
     * )
     */

    public function store(){}

    /**
     * @OA\Get(
     *     path="/api/application-statuses/{id}",
     *     tags={"Application Statuses"},
     *     summary="Получить статус заявки",
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
     *     path="/api/application-statuses/{id}",
     *     tags={"Application Statuses"},
     *     summary="Обновить статус заявки",
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
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название статуса",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(response=200, description="Updated")
     * )
     */

    public function update(){}

    /**
     * @OA\Delete(
     *     path="/api/application-statuses/{id}",
     *     tags={"Application Statuses"},
     *     summary="Удалить статус заявки",
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