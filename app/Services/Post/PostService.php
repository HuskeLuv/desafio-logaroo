<?php

namespace App\Services\Post;

use App\Models\Post;
use App\Traits\HttpRequest;
use Exception;

class PostService{
  use HttpRequest;
  
  public function create(array $postData): Post{
    if(!$post= Post::create($this->formatTags($postData))){
      throw new Exception('Unable to create Post');
  }
    return $post;
  }
}