<?php

namespace App\Observers;

use App\Follow;

class FollowObserver
{
    public function created(Follow $follow)
    {
        $follow->follower->increment('following_count');
        $follow->target_user->increment('follower_count');
    }

    public function deleted(Follow $follow)
    {
        $follow->follower->decrement('following_count');
        $follow->target_user->decrement('follower_count');
    }
}
