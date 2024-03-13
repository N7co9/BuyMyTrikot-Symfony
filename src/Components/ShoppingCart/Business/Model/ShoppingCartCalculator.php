<?php
declare(strict_types=1);

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartExpensesDto;

class ShoppingCartCalculator
{
    private const SHIPPING_COST = 4.95;

    public function calculateExpenses(array $shoppingCartItemDtoList, string $deliveryMethod): ShoppingCartExpensesDto
    {
        $tax = 0;
        $subTotal = 0;
        $shippingCost = self::SHIPPING_COST;

        if ($deliveryMethod === 'Express')
        {
            $shippingCost = self::SHIPPING_COST + 11.05;
        }

        foreach ($shoppingCartItemDtoList as $shoppingCartItemDto) {
            $subTotal += $shoppingCartItemDto->price * $shoppingCartItemDto->quantity;
            $tax = $subTotal * 0.19;
        }

        return new ShoppingCartExpensesDto(
            tax: $tax,
            subTotal: $subTotal,
            shipping: $shippingCost,
            total: $subTotal + $tax + $shippingCost
        );
    }
}