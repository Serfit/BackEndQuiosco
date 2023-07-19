<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegistroRequest $request){
        //VALIDAR EL REGISTRO
        $data = $request->validated();

        //CREAR EL USUARIO
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        //RETORNAR UNA RESPUESTA
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }

    public function login(LoginRequest $request){
        $data = $request->validated();

        //REVISAR EL PASSWORD

        if(!Auth::attempt($data)) {
            return response([
                'errors' => ['El email o el password son incorrectos']
            ], 422);
        }
        //AUTENTICAR EL USUARIO
        $user = Auth::user();

        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }
    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'user' => null
        ];
    }
}
