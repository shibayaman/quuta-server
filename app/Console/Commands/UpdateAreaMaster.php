<?php

namespace App\Console\Commands;

use App\AreaSmallMaster;
use Illuminate\Console\Command;

class UpdateAreaMaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gurunavi:update-area-master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ぐるなびのエリアマスタを更新';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $gurunavi = new AreaSmallMaster();
        $gurunavi->truncateTable();
        $gurunavi->insertTable();
        $this->info('Area master updated successfully');
    }
}
