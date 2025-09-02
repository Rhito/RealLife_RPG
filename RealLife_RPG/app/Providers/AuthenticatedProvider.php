<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\User;
use App\Policies\TaskCompletionPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthenticatedProvider extends ServiceProvider
{

    protected $policies = [
        User::class => UserPolicy::class,
        Task::class => TaskPolicy::class,
        TaskCompletion::class => TaskCompletionPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('update-profile', function (User $userAuth, User $user) {
            return $userAuth->id === $user->id;
        });
    }
}
