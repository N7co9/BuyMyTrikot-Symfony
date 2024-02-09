<?php

namespace App\Components\ShoppingCart\Persistence\Mapping;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use App\Entity\ShoppingCart as ShoppingCartEntity;

class ShoppingCartMapping
{
    public function mapEntityToDto(ShoppingCartEntity $shoppingCart): ShoppingCartSaveDTO
    {
        return new ShoppingCartSaveDTO(
            quantity: $shoppingCart->getQuantity(),
            itemId: $shoppingCart->getItemId(),
            userId: $shoppingCart->getUserId(),
            id: $shoppingCart->getId(),
        );
    }

    public function mapDtoToEntity(ShoppingCartSaveDTO $shoppingCartDto, ShoppingCartEntity $shoppingCartEntity): ShoppingCartEntity
    {
        $shoppingCartEntity->setQuantity($shoppingCartDto->quantity);
        $shoppingCartEntity->setItemId($shoppingCartDto->itemId);
        $shoppingCartEntity->setUserId($shoppingCartDto->userId);

        return $shoppingCartEntity;
    }

}