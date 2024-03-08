<?php

namespace App\Components\ShoppingCart\Business;

namespace App\Components\ShoppingCart\Business;


use App\Components\ShoppingCart\Business\Model\CartWriteInterface;
use App\Components\ShoppingCart\Business\Model\ShoppingCartCalculator;
use App\Components\ShoppingCart\Business\Model\ShoppingCartRead;
use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartExpensesDto;
use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use Symfony\Component\HttpFoundation\Request;

class ShoppingCartBusinessFacade implements ShoppingCartBusinessFacadeInterface
{

    public function __construct(
        private readonly CartWriteInterface     $cartWrite,
        private readonly ShoppingCartRead       $shoppingCartRead,
        private readonly ShoppingCartCalculator $shoppingCartCalculator,
    )
    {
    }

    public function getCart(Request $request): array
    {
        return $this->shoppingCartRead->getCart($request);
    }

    public function saveItemToCart(Request $request): void
    {
        $this->cartWrite->save($request);
    }


    public function calculateExpenses(array $shoppingCartItemDtoList): ShoppingCartExpensesDto
    {
        return $this->shoppingCartCalculator->calculateExpenses($shoppingCartItemDtoList);
    }

    public function remove(Request $request): void
    {
        $this->cartWrite->remove($request);

    }
}
