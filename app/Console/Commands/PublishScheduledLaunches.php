<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductLaunchService;

use App\Models\ProductLaunch;

class PublishScheduledLaunches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'launches:publish';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flip scheduled launches to live when their time arrives';

    /**
     * Execute the console command.
     */
    public function handle(ProductLaunchService $service)
    {
        $count =  $service->publishDue();
         $this->info("Published {$count} launch(es).");

        return self::SUCCESS;
        
    }
}
