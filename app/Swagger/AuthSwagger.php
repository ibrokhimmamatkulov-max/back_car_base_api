<?php

namespace App\Swagger;

class AuthSwagger
{
   /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Регистрация пользователя",
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         required=true,
     *         description="Имя пользователя",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         required=true,
     *         description="Фамилия пользователя",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="patronymic",
     *         in="query",
     *         required=false,
     *         description="Отчество пользователя",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         description="Пароль пользователя",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         required=true,
     *         description="Роль пользователя (Admin, Manager, Driver и т.д.)",
     *         @OA\Schema(type="string", example="Admin")
     *     ),
     *
     *     @OA\Response(response=200, description="Пользователь успешно зарегистрирован"),
     *     @OA\Response(response=422, description="Ошибка валидации данных")
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Авторизация пользователя",
     *
     *     @OA\Parameter(
     *         name="login",
     *         in="query",
     *         required=true,
     *         description="Логин пользователя",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         description="Пароль пользователя",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(response=200, description="Успешная авторизация"),
     *     @OA\Response(response=401, description="Неверный email или пароль")
     * )
     */
    public function login() {}

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Выход пользователя из системы",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(response=200, description="Пользователь успешно вышел из системы"),
     *     @OA\Response(response=401, description="Пользователь не авторизован")
     * )
     */
    public function logout() {}
}
