<?php

namespace App\Console\Commands;

use App\CategoryLargeMaster;
use Illuminate\Console\Command;

class UpdateCategoryMaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gurunavi:update-category-master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ぐるなびのカテゴリマスタを更新';

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
        $gurunavi = new CategoryLargeMaster();
        $gurunavi->truncateTable();
        $gurunavi->insertTable();
        $this->info('Category master updated successfully');
    }
}
