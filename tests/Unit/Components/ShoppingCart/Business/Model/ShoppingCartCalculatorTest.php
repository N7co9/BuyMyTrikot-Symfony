<?php
declare(strict_types=1);

namespace App\Tests\Unit\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Business\Model\ShoppingCartCalculator;
use App\Components\ShoppingCart\Dto\ShoppingCartItemDto;
use Monolog\Test\TestCase;

class ShoppingCartCalculatorTest extends TestCase
{
    public function testCalculateExpenses(): void
    {
        $shoppingCartItemDtoList =
            [
                new ShoppingCartItemDto
                (
                    id: 0, userId: 3, quantity: 3, name: 'prd1', price: 99.44, thumbnail: '', itemId: 44
                ),
                new ShoppingCartItemDto
                (
                    id: 0, userId: 3, quantity: 1, name: 'prd2', price: 53.44, thumbnail: '', itemId: 44
                ),
                new ShoppingCartItemDto
                (
                    id: 0, userId: 3, quantity: 5, name: 'prd3', price: 22.44, thumbnail: '', itemId: 44
                ),
            ];

        $shoppingCartCalculator = new ShoppingCartCalculator();

        $res = $shoppingCartCalculator->calculateExpenses($shoppingCartItemDtoList);

        self::assertSame(552.1124, $res->total);
        self::assertSame(104.90135599999999, $res->tax);
    }
}