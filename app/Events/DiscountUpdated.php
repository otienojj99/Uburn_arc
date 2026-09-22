<?php

namespace App\Events;

use App\Models\Discount;

class DiscountUpdated
{
    public $discount;

    public function __construct(Discount $discount)
    {
        $this->discount = $discount;
    }
}
