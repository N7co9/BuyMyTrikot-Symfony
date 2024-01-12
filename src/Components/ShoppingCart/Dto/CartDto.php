<?php

namespace App\Components\ShoppingCart\Dto;

class CartDto
{
    /**
     * @var ShoppingCartItemDto[]
     */
    public readonly array $shoppingCartList;
    public function __construct(
        array                                  $shoppingCartList,
        public readonly ShoppingCartDetailsDTO $cartCostDto,
    )
    {
        foreach ($shoppingCartList as $item) {
            if(!$item instanceof ShoppingCartItemDto) {
                throw new \RuntimeException(
                    sprintf('Class %s ist not instance of %s', $item::class, ShoppingCartItemDto::class)
                );
            }
        }

        $this->shoppingCartList = $shoppingCartList;
    }
}