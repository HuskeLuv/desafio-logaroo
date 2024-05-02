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

    public function index(Request $request)
    {
        try {
            $post = $this->service->getAll($request);
            return $post;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function store(PostRequest $request)
    {
        try {
            $post = $this->service->create(CreatePostDTO::makeFromRequest($request));
            return $this->success('PostCreated Successfuly', 201, [$post]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

    }

    public function update(PostRequest $request)
    {
        try {
            $post = $this->service->update(UpdatePostDTO::makeFromRequest($request));
            return $this->success('Post Updated Successfuly', 201, [$post]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
