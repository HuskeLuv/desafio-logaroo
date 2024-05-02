<?php

namespace App\Services\Post;

use App\DTO\CreatePostDTO;
use App\DTO\UpdatePostDTO;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;
use App\Traits\HttpRequest;
use Exception;
use stdClass;

class PostService{

  public function __construct(
    protected PostRepositoryInterface $repository
  ){
  }

  use HttpRequest;

  public function getAll(string $filter = null): array {
    return $this->repository->getAll($filter);
  }

  public function findOne(string $id): stdClass|null {
    return $this->repository->findOne($id);
  }

  public function create(CreatePostDTO $dto): stdClass{
    return $this->repository->create($dto);
  }

  public function update (UpdatePostDTO $dto): stdClass
    {
      return $this->repository->update($dto);
    }
    
    public function delete(string $id): void {
      $this->repository->delete($id);
    }
}