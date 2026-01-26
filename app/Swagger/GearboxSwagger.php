<?php

namespace App\Swagger;

class GearboxSwagger
{
    /**
     * @OA\Get(
     *     path="/api/gearboxes",
     *     tags={"Gearboxes"},
     *     summary="Список коробок передач",
     *     @OA\Response(response=200, description="OK")
     * )
     */

    public function index(){}

    /**
     * @OA\Post(
     *     path="/api/gearboxes",
     *     tags={"Gearboxes"},
     *     summary="Создать коробку передач",
     *
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=true,
     *         description="Код (manual, automatic)",
     *         @OA\Schema(type="string", example="automatic")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название",
     *         @OA\Schema(type="string", example="Автомат")
     *     ),
     *
     *     @OA\Response(response=201, description="Created")
     * )
     */

    public function store(){}

    /**
     * @OA\Get(
     *     path="/api/gearboxes/{id}",
     *     tags={"Gearboxes"},
     *     summary="Получить коробку передач",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID коробки передач",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */

    public function show(){}

    /**
     * @OA\Put(
     *     path="/api/gearboxes/{id}",
     *     tags={"Gearboxes"},
     *     summary="Обновить коробку передач",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID коробки передач",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         required=true,
     *         description="Код",
     *         @OA\Schema(type="string", example="manual")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название",
     *         @OA\Schema(type="string", example="Механика")
     *     ),
     *
     *     @OA\Response(response=200, description="Updated")
     * )
     */

    public function update(){}

    /**
     * @OA\Delete(
     *     path="/api/gearboxes/{id}",
     *     tags={"Gearboxes"},
     *     summary="Удалить коробку передач",
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID коробки передач",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=200, description="Deleted")
     * )
     */

    public function destroy(){}
}