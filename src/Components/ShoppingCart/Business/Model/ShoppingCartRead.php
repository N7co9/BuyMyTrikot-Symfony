<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Dto\ShoppingCartDetailsDTO;
use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Global\Persistence\Repository\ItemRepository;

class ShoppingCartRead
{
    public function __construct(
        private readonly ShoppingCartRepository $shoppingCartRepository,
        private readonly ItemRepository $itemRepository,
    )
    {
    }

    public function getCart(int $userId)
    {
        $shoppingCartSaveDTOArray = $this->shoppingCartRepository->findByUserId($userId);
    }

    private function mapShoppingCartSaveDTO2shoppingCartItemDTO(array $shoppingCartSaveDTOArray)
    {
        foreach ($shoppingCartSaveDTOArray as $shoppingCartSaveDTO)
        {
            new ShoppingCartDetailsDTO(
                price: $this->itemRepository->findOneByItemId($shoppingCartSaveDTO->itemId)->getPrice(),
                thumbnail: $this->itemRepository->findOneByItemId($shoppingCartSaveDTO->itemId)->getThumbnail());
        }
    }



    // TDD Anwenden
    // shopnngCart repostory -> findAllItemsByUserId: ShoppingCartDto[]
    // ProductDetail holen, mappen zum ShopingCartItemDto
    // berechnen
    // DTO erzeigen
    // liefern
}