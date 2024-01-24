<?php

namespace App\Components\ShoppingCart\Business;

namespace App\Components\ShoppingCart\Business;


use App\Components\ShoppingCart\Business\Model\CartWriteInterface;
use App\Components\ShoppingCart\Business\Model\ShoppingCartCalculator;
use App\Components\ShoppingCart\Business\Model\ShoppingCartRead;
use App\Components\ShoppingCart\Dto\ShoppingCartExpensesDto;
use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
use App\Entity\ShoppingCart;

class ShoppingCartBusinessFacade implements ShoppingCartBusinessFacadeInterface
{

    public function __construct(
        private readonly CartWriteInterface     $cartWrite,
        private readonly ShoppingCartRead       $shoppingCartRead,
        private readonly ShoppingCartCalculator $shoppingCartCalculator,
    )
    {
    }

    public function getCart(int $userId): array
    {
        return $this->shoppingCartRead->getCart($userId);
    }

    public function saveItemToCart(ShoppingCartSaveDTO $shoppingCartDto): void
    {
        $this->cartWrite->save($shoppingCartDto);
    }

    public function calculateExpenses(array $shoppingCartItemDtoList): ShoppingCartExpensesDto
    {
        return $this->shoppingCartCalculator->calculateExpenses($shoppingCartItemDtoList);
    }

    public function remove(int $itemId): void
    {
        $this->cartWrite->remove($itemId);
    }
}
