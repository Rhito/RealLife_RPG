<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\Contracts\UserItemRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Repositories\Contracts\TaskCompletionRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;

class UserStatService
{

    protected const STARTER_GOLD = 100;
    protected const STARTER_LEVEL = 1;
    protected const STARTER_EXP = 0;
    protected const STARTER_HP = 50;

    protected const STARTER_ITEMS = [
        // 'item_id' => quantity
    ];

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserItemRepositoryInterface $userItemRepository,
        protected ItemRepositoryInterface $itemRepository,
        protected TaskRepositoryInterface $taskRepository,
        protected TaskCompletionRepositoryInterface $taskCompletionRepository
    ) {}
}
