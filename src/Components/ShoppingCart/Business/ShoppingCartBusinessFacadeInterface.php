<?php

namespace App\Components\ShoppingCart\Business;

use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;

interface ShoppingCartBusinessFacadeInterface
{
    public function saveItemToCart(ShoppingCartSaveDTO $shoppingCartDto): void;
}