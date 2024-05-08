<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase {
  use RefreshDatabase;

  

    public function test_user_can_get_all_posts() {
        $token = $this->authUser();
        Post::factory(10)->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('posts.index'));

        $response->assertStatus(200)
                ->assertJsonStructure([['id', 'title', 'author', 'content', 'tags']]);
    }

    public function test_user_can_search_posts_by_tag() {
        $token = $this->authUser();
        $postWithTag1 = Post::factory()->create(['tags' => 'tag1']);
        Post::factory()->create(['tags' => 'tag2']);
        Post::factory()->create();
        
        $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
        ])->getJson(route('posts.index', ['tag' => 'tag1']));
        
        $response->assertStatus(200);

        $responseData = $response->json();
    
        $this->assertEquals(1, count($responseData));
    
        $postWithTag1Data = $responseData[0];
    
        $this->assertEquals($postWithTag1->title, $postWithTag1Data['title']);
        $this->assertEquals($postWithTag1->author, $postWithTag1Data['author']);
        $this->assertEquals($postWithTag1->content, $postWithTag1Data['content']);
        $this->assertEquals('tag1', $postWithTag1Data['tags']);
    }
    
    public function test_user_can_create_new_post() {
        $token = $this->authUser();

        $postData = [
            'title' => 'Novo Post',
            'author' => 'John Doe',
            'content' => 'Conteúdo do novo post',
            'tags' => explode(',', 'tag1,tag2'),
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            ])->postJson(route('posts.store'), $postData);
            
        $response->assertStatus(201);
            
        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'author' => $postData['author'],
            'content' => $postData['content'],
            'tags' => implode(',', $postData['tags']),
        ]);
    }

    public function test_user_can_edit_a_post() {
        $token = $this->authUser();
        $post = Post::factory()->create();

        $postData = [
            'title' => 'Novo Post',
            'author' => 'John Doe',
            'content' => 'Conteúdo do novo post',
            'tags' => explode(',', 'tag1,tag2'),
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            ])->putJson(route('posts.update', ['post' => $post->id]), $postData);
            
        $response->assertStatus(200);
            
        $this->assertDatabaseHas('posts', [
            'id' => $post->id, 
            'title' => $postData['title'],
            'author' => $postData['author'],
            'content' => $postData['content'],
            'tags' => implode(',', $postData['tags']),
        ]);
    }

    public function test_user_can_delete_a_post() {
        $token = $this->authUser();
        $post = Post::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson(route('posts.destroy', ['post' => $post->id]));
        
        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}