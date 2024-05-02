<?php

namespace App\Repositories;

use App\DTO\CreatePostDTO;
use App\DTO\UpdatePostDTO;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;
use stdClass;

class PostEloquentORM implements PostRepositoryInterface {

  public function __construct(
    protected Post $model
  )
  {}
  public function getAll(string $filter = null): array {
    return $this->model
                ->where(function($query) use ($filter) {
                  if ($filter) {
                    $query->where('tags', 'LIKE','%'.$filter.'%');
                  }
                })
                ->get()
                ->toArray();
  }

  public function findOne(string $id): stdClass|null {
    $post = $this->model->find($id);
    if(!$post){
      return null;
    }
    return (object) $post->toArray();
  }

  public function delete(string $id): void {
    $this->model->findOrFail($id)->delete();
  }

  public function create(CreatePostDTO $dto): stdClass {
    $post = $this->model->create(
      (array) $dto
    );
    return (object) $post->toArray();
  }

  public function update(UpdatePostDTO $dto): stdClass {
    if(!$post = $this->model->find($dto->id)){
      return null;
    }
    $post->update(
      (array) $dto
    );
    return (object) $post->toArray();
  }
}