<?php

namespace App\Providers;

use App\Repositories\Achievement\AchievementRepository;
use App\Repositories\Contracts\AdminLogRepositoryInterface;
use App\Repositories\Contracts\AdminRepositoryInterface;
use App\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Admin\AdminLogRepository;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\Contracts\AchievementRepositoryInterface;
use App\Repositories\Item\ItemRepository;
use App\Repositories\ItemCategory\ItemCategoryRepository;
use App\Repositories\Task\TaskRepository;

use App\Repositories\User\UserRepository;
use App\Repositories\Contracts\StatLogRepositoryInterface;
use App\Repositories\StatLog\StatLogRepository;
use App\Repositories\Contracts\UserAchievementRepositoryInterface;
use App\Repositories\User\UserAchievementRepository;
use App\Repositories\Contracts\UserItemRepositoryInterface;
use App\Repositories\User\UserItemRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AdminLogRepositoryInterface::class, AdminLogRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(AchievementRepositoryInterface::class, AchievementRepository::class);
        $this->app->bind(ItemCategoryRepositoryInterface::class, ItemCategoryRepository::class);
        $this->app->bind(StatLogRepositoryInterface::class, StatLogRepository::class);
        $this->app->bind(UserAchievementRepositoryInterface::class, UserAchievementRepository::class);
        $this->app->bind(UserItemRepositoryInterface::class, UserItemRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
