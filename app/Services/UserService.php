<?php

namespace App\Services;

use App\Models\User;
use App\Contracts\BaseInterface;

class UserService
{

  public function __construct(protected BaseInterface $repository)
  {
  }

  public function store(array $data): User
  { 
    $user = $this->repository->massDataSave('User', $data);
    return $user;
  }

  public function update(array $data, int $id)
  {
    $this->repository->update('User', $data, $id);
  }

  public function delete(int $id)
  {
    $this->repository->delete('User', $id);
  }
}
