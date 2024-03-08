<?php

namespace App\Components\ShoppingCart\Business;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use Symfony\Component\HttpFoundation\Request;

interface ShoppingCartBusinessFacadeInterface
{
    public function saveItemToCart(Request $request): void;

    public function remove(Request $request): void;
}