<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Desafio Logaroo",
    description: "Documentação da API criada para o Desafio de backend developer da empresa Logaroo",
    contact: new OA\Contact(
        email: "huske.xp@gmail.com",
        name: "Wellington Santos",
    ),
    license: new OA\License(
        name: "Apache 2.0",
        url: "http://www.apache.org/licenses/LICENSE-2.0.html"
    ),
)]

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){}

    #[OA\Post(
        path: "/api/register",
        tags: ["JWT Authentication"],
        summary: "Registra um novo usuário",
        description: "Esse endpoint registra um novo usuário",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    required: ["email", "password", "name", "password_confirmation"],
                    type: "object",
                    properties: [
                        new OA\Property(property:"name", description:"Nome do Usuário", type: "string", example: "Exemplo Teste"),
                        new OA\Property(property:"email", description:"Email do Usuário",type: "string", example: "teste@example.org"),
                        new OA\Property(property:"password", description:"Senha do Usuário",type: "string", example: "#sdasdssdaAA@"),
                        new OA\Property(property:"password_confirmation", description:"Confirmação da senha do Usuário",type: "string", example: "#sdasdssdaAA@")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: "200",
                description: "Usuário Cadastrado",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property:"message", type: "string", example: "Usuário Cadastrado com sucesso")
                            ]
                    )
                )
            ),
            new OA\Response(
                response: "422",
                description: "Campos Incorretos",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property:"message",type: "string", example: "Esse email já foi cadastrado"),
                            new OA\Property(property:"errors",type: "string", example: "...")
                        ]
                    )
                )
            )
        ]
    )]
    public function register(RegistrationRequest $request){
        try {
            $userData = $request->only('name', 'email', 'password');
            $user = $this->authService->register($userData);
            return $user;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    public function login(LoginRequest $request): JsonResponse {
        try {
            $credentials = $request->only('email', 'password');
            $tokenResponse = $this->authService->login($credentials);
            return response()->json($tokenResponse);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout(true);

        return response()->json(['message' => 'Successfully logged out']);
    }

}
