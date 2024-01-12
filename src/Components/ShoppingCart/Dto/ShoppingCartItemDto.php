<?php

namespace App\Components\ShoppingCart\Dto;

// this is the complete Item DTO
class ShoppingCartItemDto
{
        public function __construct(
            public readonly int $id,
            public readonly int $userId,
            public readonly int $quantity,
            public readonly string $name,
            public readonly float $price,
            public readonly string $thumbnail,
        )
        {
        }
}