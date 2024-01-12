<?php

namespace App\Components\ShoppingCart\Business;

namespace App\Components\ShoppingCart\Business;


use App\Components\ShoppingCart\Business\Model\CartWriteInterface;
use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;

class ShoppingCartBusinessFacade implements ShoppingCartBusinessFacadeInterface
{

    public function __construct(
        private readonly CartWriteInterface $cartWrite,
    )
    {
    }

    public function saveItemToCart(ShoppingCartSaveDTO $shoppingCartDto): void
    {
        $this->cartWrite->save( $shoppingCartDto);
    }

}
