<?php

namespace App\Providers;

use App\Good;
use App\Thread;
use App\Follow;
use App\Observers\GoodObserver;
use App\Observers\ThreadObserver;
use App\Observers\FollowObserver;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();

        Good::observe(GoodObserver::class);
        Thread::observe(ThreadObserver::class);
        Follow::observe(FollowObserver::class);
    }
}
