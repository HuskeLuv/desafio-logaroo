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

class PostController extends Controller
{
    public function __construct(
        protected PostService $service
    ){}

    use HttpResponses;
    use HttpRequest;

    public function index(Request $request){
        try {
            $filter = $request->input('tag', '');
            $post = $this->service->getAll($filter);
            return $post;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function store(PostRequest $request){
        try {
            $post = $this->service->create(CreatePostDTO::makeFromRequest($request));
            return $this->success('PostCreated Successfuly', 201, [$post]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(PostRequest $request, $id){
        try {
            $request->merge(['id' => $id]);
            $post = $this->service->update(UpdatePostDTO::makeFromRequest($request));
            return $this->success('Post Updated Successfuly', 200, [$post]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(string $id){
        try {
            $this->service->delete($id);
            return $this->success('No Content', 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
