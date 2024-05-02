<?php

namespace App\DTO;

use App\Http\Requests\PostRequest;
use App\Traits\HttpRequest;

class CreatePostDTO {
  use HttpRequest;
  public function __construct(
    public string $title,
    public string $author,
    public string $content,
    public string $tags
  )
  {
  }

  public static function makeFromRequest(PostRequest $request): self {
    $tags = implode(',', $request->tags);
    return new self(
      $request->title,
      $request->author,
      $request->content,
      $tags
    );
  }
}