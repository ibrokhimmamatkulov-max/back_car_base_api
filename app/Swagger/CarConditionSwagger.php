<?php 

namespace App\Swagger;

class CarConditionSwagger
{
     /**
     * 
     * @OA\Get(
     *     path="/api/car-settings/car-conditions",
     *     summary="GET cars",
     *     tags={"CarCondition"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *      name="limit",
     *      in="query",
     *      required=false,
     *      @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="filter_id",
     *         in="query",
     *         description="Фильтр по id продукта",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="filter_id_condition",
     *         in="query",
     *         description="Фильтр по условию id продукта",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter_name",
     *         in="query",
     *         description="Фильтр по имени продукта",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter_name_condition",
     *         in="query",
     *         description="Фильтр по условию имени продукта",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter_level",
     *         in="query",
     *         description="Фильтр по услувию level продукта",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="filter_level_condition",
     *         in="query",
     *         description="Фильтр по level продукта",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *               @OA\Property(property="id", type="integer", example=1),
     *               @OA\Property(property="name", type="string", example="Some name"),
     *               @OA\Property(property="level", type="integer", example=1),
     *             )),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ресурс не найден"
     *     )
     * )
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){}

     /**
     * 
     * @OA\Post(
     *     path="/api/car-settings/car-conditions",
     *     summary="Create",
     *     tags={"CarCondition"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Some name"),
     *                 @OA\Property(property="level", type="integer", example=1), 
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *            @OA\Property(property="id", type="integer", example=1),
     *            @OA\Property(property="name", type="string", example="Some name"),
     *            @OA\Property(property="level", type="integer", example=1), 
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ошибка в запросе",
     *         @OA\JsonContent(
     *             
     *         )
     *     ),
     *     
     * )
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(){}

     /**
     * 
     * @OA\Get(
     *  path="/api/car-settings/car-conditions/{car_condition}/edit",
     *  operationId="/api/car-settings/car-conditions/{car_condition}/edit",
     *  tags={"CarCondition"},
     *  summary="Get car-condition by id",
     *  security={{"bearerAuth":{}}},
     *  @OA\Parameter(
     *   name="car_condition",
     *   in="path",
     *   required=true,
     *   @OA\Schema(type="integer"),
     *
     *  ),
     *  @OA\Response(response=200, description="Success",@OA\MediaType(mediaType="application/json",)),
     *  @OA\Response(response=401,description="Unauthenticated"),
     *  @OA\Response(response=400,description="Bad Request"),
     *  @OA\Response(response=404,description="not found"),
     *  @OA\Response(response=403,description="Forbidden")
     *)
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarCondition  $carCondition
     * @return \Illuminate\Http\Response
     */
    public function edit(){}

     /**
     * 
     * @OA\PATCH(
     *     path="/api/car-settings/car-conditions/{car_condition}",
     *     summary="Update",
     *     tags={"CarCondition"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="car_condition",
     *         in="path",
     *         description="ID ресурса",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Описание поля 1"
     *             ),
     *             @OA\Property(
     *                 property="level",
     *                 type="integer",
     *                 description="Описание поля 2"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное обновление ресурса"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ресурс не найден"
     *     )
     * )
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarCondition  $carCondition
     * @return \Illuminate\Http\Response
     */
    public function update(){}
}