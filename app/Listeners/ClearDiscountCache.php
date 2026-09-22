<?php

namespace App\Listeners;

use App\Events\DiscountUpdated;
use App\Services\DiscountService;

class ClearDiscountCache
{
    public function handle(DiscountUpdated $event): void
    {
        DiscountService::clearCaches();
    }
}
