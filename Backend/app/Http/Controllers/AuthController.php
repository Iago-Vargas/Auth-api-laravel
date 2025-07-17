<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Declaração do construtor
    public function __construct(protected User $userModel){ }

    /* Aqui é o metodo para fazermos o Registro dos usuarios */
    public function register(Request $request){

        $authRegisterFields = $request -> validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        // Aqui vamos criptografar a senha que o user colocou
        $authRegisterFields['password'] = Hash::make($authRegisterFields['password']);
        // Passamos os dados para o userRegistered, criando o nosso usuario
        $userRegistered = $this->userModel->create($authRegisterFields);
        // Criamos um token Sanctum associado ao usuario
        $token = $userRegistered->createToken($request->name)->plainTextToken;

        // Retorno do Usuario + Token
        return response()->json([
            'user' => $userRegistered,
            'token' => $token
        ], 201);
    }

    // Agora vamos criar o metodo de login do usuario
    public function  login(Request $request){
        // Chamamos o Validade para validar se os dados batem com os do banco
        $request -> validate([
            //  Required pois tem que ter email, tipo email tem que ser o digitado e se ele existe em users no BD
            'email' => 'required|email|exists:users',
            // Senha tem que ser ter, requirida
            'password' => 'required'
        ]);
}
}
