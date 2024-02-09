<?php

namespace App\Components\ShoppingCart\Persistence\Dto;

class ShoppingCartItemDto
{
    public function __construct(
        public readonly int    $id,
        public readonly int    $userId,
        public readonly int    $quantity,
        public readonly string $name,
        public readonly float  $price,
        public readonly string $thumbnail,
        public readonly int    $itemId,
    )
    {
    }
}