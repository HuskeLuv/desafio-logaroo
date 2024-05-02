<?php

namespace App\Repositories;

use App\DTO\CreatePostDTO;
use App\DTO\UpdatePostDTO;
use stdClass;

interface PostRepositoryInterface {

  public function getAll(string $filter = null): array;
  public function findOne(string $id): stdClass|null;
  public function delete(string $id): void;
  public function create(CreatePostDTO $dto): stdClass|null;
  public function update(UpdatePostDTO $dto): stdClass|null;
}