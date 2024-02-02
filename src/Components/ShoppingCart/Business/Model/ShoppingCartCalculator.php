<?php
declare(strict_types=1);

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Dto\ShoppingCartExpensesDto;

class ShoppingCartCalculator
{
    private const SHIPPING_COST = 4.95;

    public function calculateExpenses(array $shoppingCartItemDtoList): ShoppingCartExpensesDto
    {
        $tax = 0;
        $subTotal = 0;

        foreach ($shoppingCartItemDtoList as $shoppingCartItemDto) {
            $subTotal += $shoppingCartItemDto->price * $shoppingCartItemDto->quantity;
            $tax = $subTotal * 0.19;
        }

        return new ShoppingCartExpensesDto(
            tax: $tax,
            subTotal: $subTotal,
            shipping: self::SHIPPING_COST,
            total: $subTotal + $tax + self::SHIPPING_COST
        );
    }
}