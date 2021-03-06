<?php

namespace App\Observers;

use App\Good;

class GoodObserver
{
    public function created(Good $good)
    {
        $post = $good->post;
        $post->incrementGoodCount();
        $post->user->incrementGoodCount();
    }

    public function deleted(Good $good)
    {
        $post = $good->post;
        $post->incrementGoodCount(-1);
        $post->user->incrementGoodCount(-1);
    }
}
