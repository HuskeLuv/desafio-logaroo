<?php

namespace App\DTO;

use App\Http\Requests\PostRequest;
use App\Traits\HttpRequest;

class UpdatePostDTO {
  use HttpRequest;
  public function __construct(
    public string $id,
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
      $request->id,
      $request->title,
      $request->author,
      $request->content,
      $tags
    );
  }
}