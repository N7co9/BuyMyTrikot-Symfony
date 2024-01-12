<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;

interface CartWriteInterface
{
    public function save(ShoppingCartSaveDTO $shoppingCartDto): void;

}