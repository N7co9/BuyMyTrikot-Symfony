<?php

namespace App\Components\ShoppingCart\Dto;

// this DTO will be used for importing ShoppingCart Data from the DB. It does not contain all necessary info.
class ShoppingCartSaveDTO
{
    public function __construct(
        public readonly int $quantity,
        public readonly int $itemId,
        public readonly int $userId,
        public readonly int $id = 0,
    )
    {
    }
}