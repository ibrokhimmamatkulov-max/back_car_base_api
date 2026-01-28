<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'patronymic'     => 'nullable|string|max:255',
            'password' => 'required|min:8',
            'role'     => 'required|string|exists:roles,name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $validated=$validator->validated();
        $login=$this->generateLogin();
        $user = User::create([
            'first_name'     => $validated['first_name'] ?? null,
            'last_name'     => $validated['last_name'] ?? null,
            'patronymic'     => $validated['patronymic'] ?? null,
            'login'    => $login,
            'password' => Hash::make($validated['password']),
        ]);

        $role = Role::where('name', $validated['role'])->first();
        $user->roles()->attach($role->id);

        // $token = $user->createToken('api-token')->accessToken;

        return response()->json([
            'login' => $login,
            'user'  => $user->load('roles'),
        ], 201);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login'    => 'required|string',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $validated=$validator->validated();
        $user = User::where('login', $validated['login'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $token = $user->createToken('api-token')->accessToken;

        return response()->json([
            'token' => $token,
            'user'  => $user->load('roles'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    private function generateLogin(int $length = 8): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
        do {
            $login = '';
            for ($i = 0; $i < $length; $i++) {
                $login .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (User::where('login', $login)->exists());
    
        return $login;
    }
    
}
