<?php

namespace App\Components\ShoppingCart\Dto;

// This is a ShoppingCartDetailsDTO that will fetch data directly from the Item Entity.
// This allows the item details to be changed directly by changing the property of the Item Entity.

class ShoppingCartDetailsDTO
{
    public function __construct(
        public readonly float $price,
        public readonly string $thumbnail
    )
    {
    }
}