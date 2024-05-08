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
    )
)]

#[OA\SecurityScheme(
    securityScheme: "bearer",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ){}

    #[OA\Post(
        tags: ["Auth"],
        summary: "Registra um novo usuário",
        description: "Esse endpoint registra um novo usuário",
        path: "/api/auth/register",
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
                response: "201",
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

    #[OA\Post(
        tags: ["Auth"],
        summary: "Recebe um token de autenticação",
        description: "Esse endpoint retorna um token jwt de autenticação para o usuário",
        path: "/api/auth/login",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    required: ["email", "password"],
                    type: "object",
                    properties: [
                        new OA\Property(property:"email", description:"Email do Usuário",type: "string", example: "teste@example.org"),
                        new OA\Property(property:"password", description:"Senha do Usuário",type: "string", example: "#sdasdssdaAA@")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: "200",
                description: "Usuário logado com sucesso",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property:"access_token", type: "string", example: "2|MZEBxLy1zulPtND6brlf8GOPy57Q4DwYunlibXGj"),
                            new OA\Property(property:"token_type", type: "string", example: "bearer"),
                            new OA\Property(property:"expires_in", type: "string", example: "3600"),
                            ]
                    )
                )
            ),
            new OA\Response(
                response: "422",
                description: "Credenciais  incorretas",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property:"message",type: "string", example: "As credenciais enviadas estão incorretas"),
                            new OA\Property(property:"errors",type: "string", example: "Password incorreto")
                        ]
                    )
                )
            )
        ]
    )]
    public function login(LoginRequest $request): JsonResponse {
        try {
            $credentials = $request->only('email', 'password');
            $tokenResponse = $this->authService->login($credentials);
            return response()->json($tokenResponse);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
    #[OA\Get(
        tags: ["Auth"],
        summary: "Recebe informações sobre o usuário autenticado",
        description: "Esse endpoint retorna todas as informações sobre o usuário autenticado",
        path: "/api/auth/me",
        security: [
            ["bearer" => []]
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Informações sobre o usuário autenticado",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "string", example: "1"),
                            new OA\Property(property: "name", type: "string", example: "Exemplo Teste"),
                            new OA\Property(property: "email", type: "string", example: "teste@example.org"),
                            new OA\Property(property: "email_verified_at", type: "string", example: "2024-04-29T20:52:17.000000Z"),
                            new OA\Property(property: "created_at", type: "string", example: "2024-04-29T20:52:17.000000Z"),
                            new OA\Property(property: "updated_at", type: "string", example: "2024-04-29T20:52:17.000000Z"),
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "422",
                description: "Credenciais  incorretas",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property:"message",type: "string", example: "As credenciais enviadas estão incorretas"),
                            new OA\Property(property:"errors",type: "string", example: "Password incorreto")
                        ]
                    )
                )
            )
        ]
    )]
    public function me()
    {
        return response()->json(auth()->user());
    }

    #[OA\Delete(
        tags: ["Auth"],
        summary: "Remove todos os tokens do usuário",
        description: "Esse endpoint faz o logout do usuário, revogando todos os tokens ativos dele",
        path: "/api/auth/logout",
        security: [ ["bearer" => []] ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Tokens do usuário removidos",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "Todos os tokens do usuário removidos")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "401",
                description: "Não Autorizado",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "Unauthenticated")
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "422",
                description: "Dados enviados incorretos",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "Os dados enviados estão incorretos"),
                            new OA\Property(property: "errors", type: "string", example: "...")
                        ]
                    )
                )
            )
        ]
    )]
    public function logout()
    {
        auth()->logout(true);

        return response()->json(['message' => 'Successfully logged out']);
    }

}
