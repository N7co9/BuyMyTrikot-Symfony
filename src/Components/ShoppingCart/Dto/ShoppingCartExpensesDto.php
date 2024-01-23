<?php
declare(strict_types=1);

namespace App\Components\ShoppingCart\Dto;

class ShoppingCartExpensesDto
{
    public function __construct(
        public readonly float $tax,
        public readonly float $subTotal,
        public readonly float $shipping,
        public readonly float $total
    )
    {
    }
}