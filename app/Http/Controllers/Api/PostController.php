<?php

namespace App\Http\Controllers\Api;

use App\DTO\CreatePostDTO;
use App\DTO\UpdatePostDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\Post\PostService;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Traits\HttpRequest;
use Exception;
use OpenApi\Attributes as OA;

class PostController extends Controller
{
    public function __construct(
        protected PostService $service
    ){}

    use HttpResponses;
    use HttpRequest;

    #[OA\Get(
        tags: ["Posts"],
        summary: "Retorna informações de todos os posts cadastrados",
        description: "Esse endpoint retorna todas as informações de todos os posts cadastrados",
        path: "/api/posts",
        security: [
            ["bearer" => []]
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Informações sobre posts cadastrados",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "string", example: "1"),
                            new OA\Property(property: "title", type: "string", example: "Exemplo Título"),
                            new OA\Property(property: "author", type: "string", example: "Jett Hilpert"),
                            new OA\Property(property: "content", type: "string", example: "Local app manager. Start apps within your browser, developer tool with local"),
                            new OA\Property(property: "tags", type: "string", example: "node,organizing,webapps,domain,developer,https,proxy"),
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
                            new OA\Property(property:"errors",type: "string", example: "Usuário não autorizado")
                        ]
                    )
                )
            )
        ]
    )]
    public function index(Request $request){
        try {
            $filter = $request->input('tag', '');
            $post = $this->service->getAll($filter);
            return $post;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    #[OA\Post(
        tags: ["Posts"],
        summary: "Registra um novo post",
        description: "Esse endpoint registra um novo post",
        path: "/api/posts",
        security: [
            ["bearer" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    required: ["title", "author", "content", "tags"],
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "string", example: "1"),
                        new OA\Property(property: "title", type: "string", example: "Exemplo Título"),
                        new OA\Property(property: "author", type: "string", example: "Jett Hilpert"),
                        new OA\Property(property: "content", type: "string", example: "Local app manager. Start apps within your browser, developer tool with local"),
                        new OA\Property(property: "tags", type: "string", example: [ "framework", "node", "http2", "https", "localhost" ]),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: "201",
                description: "Post Criado com sucesso",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "title", type: "string", example: "Exemplo Título"),
                            new OA\Property(property: "author", type: "string", example: "Jett Hilpert"),
                            new OA\Property(property: "content", type: "string", example: "Local app manager. Start apps within your browser, developer tool with local"),
                            new OA\Property(
                                property: "tags", 
                                type: "array", 
                                items: new OA\Items(
                                    type: "string",
                                    example: "framework"
                                ),
                                example: ["framework", "node", "http2", "https", "localhost"]
                            ),
                            new OA\Property(property: "id", type: "string", example: "1"),
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
                            new OA\Property(property:"message",type: "string", example: "O campo author é obrigatório"),
                            new OA\Property(property:"errors",type: "string", example: "...")
                        ]
                    )
                )
            )
        ]
    )]
    public function store(PostRequest $request){
        try {
            $post = $this->service->create(CreatePostDTO::makeFromRequest($request));
            return $this->success('PostCreated Successfuly', 201, [$post]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    #[OA\Put(
        tags: ["Posts"],
        summary: "Edita um post",
        description: "Esse endpoint edita um post existente",
        path: "/api/posts/{post}",
        security: [
            ["bearer" => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    required: ["title", "author", "content", "tags"],
                    type: "object",
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "Exemplo Título"),
                        new OA\Property(property: "author", type: "string", example: "Jett Hilpert"),
                        new OA\Property(property: "content", type: "string", example: "Local app manager. Start apps within your browser, developer tool with local"),
                        new OA\Property(
                            property: "tags", 
                            type: "array", 
                            items: new OA\Items(
                                type: "string",
                                example: "framework"
                            ),
                            example: ["framework", "node", "http2", "https", "localhost"]
                        ),
                    ]
                )
            )
        ),
        parameters: [
            new OA\Parameter(
                name: "post",
                in: "path",
                required: true,
                description: "ID do post a ser editado",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Post Criado com sucesso",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "title", type: "string", example: "Exemplo Título"),
                            new OA\Property(property: "author", type: "string", example: "Jett Hilpert"),
                            new OA\Property(property: "content", type: "string", example: "Local app manager. Start apps within your browser, developer tool with local"),
                            new OA\Property(
                                property: "tags", 
                                type: "array", 
                                items: new OA\Items(
                                    type: "string",
                                    example: "framework"
                                ),
                                example: ["framework", "node", "http2", "https", "localhost"]
                            ),
                            new OA\Property(property: "id", type: "string", example: "1"),
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
                            new OA\Property(property:"message",type: "string", example: "O campo author é obrigatório"),
                            new OA\Property(property:"errors",type: "string", example: "...")
                        ]
                    )
                )
            )
        ]
    )]
    public function update(PostRequest $request, $id){
        try {
            $request->merge(['id' => $id]);
            $post = $this->service->update(UpdatePostDTO::makeFromRequest($request));
            return $this->success('Post Updated Successfuly', 200, [$post]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    #[OA\Delete(
        tags: ["Posts"],
        summary: "Deleta um post",
        description: "Esse endpoint apaga as informações do post indicado",
        path: "/api/posts/{post}",
        security: [ ["bearer" => []] ],
        parameters: [
            new OA\Parameter(
                name: "post",
                in: "path",
                required: true,
                description: "ID do post a ser deletado",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: "204",
                description: "Post removido com sucesso",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "Post removido com sucesso")
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
                            new OA\Property(property: "message", type: "string", example: "O post indicado não existe"),
                            new OA\Property(property: "errors", type: "string", example: "...")
                        ]
                    )
                )
            )
        ]
    )]
    public function destroy(string $id){
        try {
            $this->service->delete($id);
            return $this->success('No Content', 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
