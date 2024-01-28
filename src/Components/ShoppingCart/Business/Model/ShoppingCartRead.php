<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Dto\ShoppingCartItemDto;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Items;
use App\Global\Persistence\Repository\ItemRepository;
use Doctrine\ORM\NonUniqueResultException;

class ShoppingCartRead
{
    public function __construct(
        private readonly ShoppingCartRepository $shoppingCartRepository,
        private readonly ItemRepository         $itemRepository,
    )
    {
    }

    /**
     *
     * @param int $userId
     * @return ShoppingCartItemDto[]
     * @throws NonUniqueResultException
     *
     */
    public function getCart(int $userId): array
    {
        $shoppingCartSaveDTOArray = $this->shoppingCartRepository->findByUserId($userId);
        $shoppingCartItemDtoList = [];

        foreach ($shoppingCartSaveDTOArray as $shoppingCartSaveDTO) {
            $item = $this->itemRepository->findOneByItemId($shoppingCartSaveDTO->itemId);

            if ($item instanceof Items) {
                $shoppingCartItemDTOs = new ShoppingCartItemDto(
                    id: $shoppingCartSaveDTO->id,
                    userId: $shoppingCartSaveDTO->userId,
                    quantity: $shoppingCartSaveDTO->quantity,
                    name: $item->getName(),
                    price: $item->getPrice(),
                    thumbnail: $item->getThumbnail(),
                    itemId: $item->getItemId()
                );
                $shoppingCartItemDtoList[] = $shoppingCartItemDTOs;
            }
        }
        return $shoppingCartItemDtoList;
    }
}