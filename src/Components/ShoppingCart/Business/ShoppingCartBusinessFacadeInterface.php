<?php

namespace App\Components\ShoppingCart\Business;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use Symfony\Component\HttpFoundation\Request;

interface ShoppingCartBusinessFacadeInterface
{
    public function saveItemToCart(Request $request): void;

    public function remove(Request $request): void;

    public function fetchDeliveryMethod(Request $request) : string;

    public function fetchShippingCost(Request $request) : float;

}