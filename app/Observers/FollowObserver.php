<?php

namespace App\Observers;

use App\Follow;

class FollowObserver
{
    public function created(Follow $follow)
    {
        $follow->follower->incrementFollowingCount();
        $follow->target_user->incrementFollowerCount();
    }

    public function deleted(Follow $follow)
    {
        $follow->follower->incrementFollowingCount(-1);
        $follow->target_user->incrementFollowerCount(-1);
    }
}
