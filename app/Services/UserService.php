<?php

namespace App\Services;

use App\Models\User;
use App\Contracts\BaseInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;

class UserService
{

  public function __construct(protected BaseInterface $repository)
  {
  }

  public function store(array $data, string $role): User
  {
    unset($data['role']);
    $data['is_active'] = $role === Config::get('constants.USER') ? Config::get('constants.ONE') : Config::get('constants.ZERO');
    $user = $this->repository->store('User', $data);
    event(new Registered($user));
    $user->assignRole($role);
    Auth::login($user);
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
