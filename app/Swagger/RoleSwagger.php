<?php

namespace App\Swagger;

class RoleSwagger
{
     /**
     * @OA\Get(
     *     path="/api/roles",
     *     tags={"Role"},
     *     summary="Список ролей",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список ролей"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     tags={"Role"},
     *     summary="Создать роль",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название роли",
     *         @OA\Schema(type="string", example="Manager")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         description="Описание роли",
     *         @OA\Schema(type="string", example="Менеджер системы")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Роль успешно создана"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка валидации"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     tags={"Role"},
     *     summary="Получить роль по ID",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID роли",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Данные роли"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Роль не найдена"
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Patch(
     *     path="/api/roles/{id}",
     *     tags={"Role"},
     *     summary="Обновить роль",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID роли",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=false,
     *         description="Название роли",
     *         @OA\Schema(type="string", example="Admin")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         required=false,
     *         description="Описание роли",
     *         @OA\Schema(type="string", example="Системный администратор")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Роль успешно обновлена"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Роль не найдена"
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     tags={"Role"},
     *     summary="Удалить роль",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID роли",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Роль успешно удалена"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Роль не найдена"
     *     )
     * )
     */
    public function destroy() {}
}