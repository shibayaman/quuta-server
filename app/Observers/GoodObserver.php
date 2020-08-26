<?php

namespace App\Observers;

use App\Good;

class GoodObserver
{
    public function created(Good $good)
    {
        $good->post->incrementGoodCount();
    }

    public function deleted(Good $good)
    {
        $good->post->incrementGoodCount(-1);
    }
}
