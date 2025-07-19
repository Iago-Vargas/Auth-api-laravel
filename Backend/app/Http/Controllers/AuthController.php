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
        // Agora vamos realizar a busca no BD
        $user = $this->userModel->where('email',$request->email)->first();

        // Agora o HASH verifica se a senha do BD bate com a que colocamos, por conta da criptografia
        if (!$user || !Hash::check($request->password, $user->password)){
            return ['Erro de Login' => 'As credenciais estão incorretas!'];
        }
        // Vamos realizar a criação do token associado ao usuario
        // PlainTextToken transforma o token em texto simples
        $token = $user->createToken($user->name)->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Logout do usuario
    public function logout(Request $request){
        // Vamos deletar o token do usuario
        $request->user()->tokens()->delete();

        // Mensagem de sucesso ao deslogar
        return response()->json([
            'Deslogado' => 'Você foi deslogado com sucesso!'
        ],201);
    }
}
