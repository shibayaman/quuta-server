<?php

namespace App\Observers;

use App\Thread;

class ThreadObserver
{
    public function created(Thread $thread)
    {
        $thread->post->incrementCommentCount();
    }

    public function deleted(Thread $thread)
    {
        $thread->post->incrementCommentCount(-1);
    }
}
