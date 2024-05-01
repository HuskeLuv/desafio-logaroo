<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\Post\PostService;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Traits\HttpRequest;
use Exception;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService
    ){}

    use HttpResponses;
    use HttpRequest;

    public function index(Request $request)
    {
        if(!$request->input()){
        return PostResource::collection(Post::all());
        }
        $tags = $request->input('tags');

        return Post::where('tags', 'LIKE','%'.$tags.'%')->get();
    }

    public function store(PostRequest $request)
    {
        try {
            $data = $request->only('title', 'author', 'content', 'tags');
            $post = $this->postService->create($data);
            return $this->success('PostCreated Successfuly', 201, [$post]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
