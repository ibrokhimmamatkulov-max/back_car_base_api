<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Laminas\Diactoros\ServerRequest;
use Laravel\Passport\Http\Controllers\AccessTokenController;

class OAuth2Service
{
    public function token(array $data, $type='password') : array
    {
        try{
            $req_data =  [
                'grant_type'    => $type,       // Указываем тип "refresh_token"
                'client_id'     => config('passport.grant_client.id'),      // Ваш client_id
                'client_secret' => config('passport.grant_client.secret'),  // Ваш client_secret
                'scope'         => 'web',                  // Можно оставить пустым
                'data'          => $data
            ];

            if($type == "password" && (isset($data["login"]) && isset($data["password"]))) {
                unset($req_data["data"]);
                $req_data["username"] = $data["login"];
                $req_data["password"] = $data["password"];
            }elseif($type == 'refresh_token' && isset($data["refresh_token"])){
                unset($req_data["data"]);
                $req_data["refresh_token"] = $data["refresh_token"];
            }else{
                return [
                    'status' => false,
                    'message' => 'Incorrect data to request.'
                ];
            }
    
             // Создаем новый запрос, который будет передан в AccessTokenController
             $serverRequest = new ServerRequest(
                [], // ServerParams
                [], // UploadedFiles
                null, // URI
                null, // Method
                'php://input', // Body
                [], // Headers
                [], // Cookies
                [], // QueryParams
                $req_data
            );

            $response = app(AccessTokenController::class)->issueToken($serverRequest)->getContent();
            // return [$response];
        
            return [
                'status' => true,
                'message' => 'ok',
                'data' => json_decode($response,true)
            ];
        }catch(\Exception $e) {
            Log::channel('oauth2')->error($e->getMessage(), [
                'grant_type' => $type,
                'request'    => array_diff_key($req_data, array_flip(['password', 'client_secret'])),
                'exception'  => get_class($e),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'trace'      => $e->getTraceAsString(),
            ]);
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}