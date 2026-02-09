<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function  jsonResponse($data = [], $code = 200)
    {
        if(is_array($data)) {
            $arr = [];
            foreach ($data as $key => $value) {
                $arr[$key] = $value;
            }
            return response()->json($arr, $code);
        }else{
            return response()->json($data, $code);
        }
    }
}
