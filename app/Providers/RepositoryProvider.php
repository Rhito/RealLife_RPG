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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
