<?php

namespace App\Components\ShoppingCart\Business;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartExpensesDto;
use Symfony\Component\HttpFoundation\Request;

interface ShoppingCartBusinessFacadeInterface
{
    public function saveItemToCart(Request $request): void;

    public function remove(Request $request): void;

    public function fetchDeliveryMethod(Request $request) : string;

    public function fetchShippingCost(Request $request) : float;

    public function removeAllAfterOrderSuccess(Request $request) : void;

    public function calculateExpenses(array $shoppingCartItemDtoList, string $deliveryMethod = 'Standard'): ShoppingCartExpensesDto;
    public function getCart(Request $request): array;

    public function fetchShoppingCartInformation(Request $request): ?array;

}