<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepositoryInterface $userRepo;
    /**
     * UserController constructor
     *
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function updateProfile($user, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if (isset($data['avatar'])) {
            $path = $data['avatar']->store('avatars', 'public');
            $data['avatar'] = $path;
        }
        $this->userRepo->update($user, $data);
        return $user->fresh();
    }
}
