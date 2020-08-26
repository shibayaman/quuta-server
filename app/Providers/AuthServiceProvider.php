<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('link_image', function ($user, $image) {
            return $image->post_id === null && $user->user_id === $image->user_id;
        });

        Gate::define('delete-comment', function ($user, $comment) {
            return $user->user_id === $comment->user_id;
        });

        Gate::define('delete-good', function ($user, $good) {
            return $user->user_id === $good->user_id;
        });
    }
}
