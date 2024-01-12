<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;

class ShoppingCartWrite implements CartWriteInterface
{
    public function __construct(
        private readonly ShoppingCartRepository $shoppingCartRepository,
        private readonly ShoppingCartEntityManager $shoppingCartEntityManager,
    )
    {
    }

    public function save(ShoppingCartSaveDTO $shoppingCartDto): void
    {
        $shoppingCartDtoFromDb = $this->shoppingCartRepository->findOneByUserIdAndItemId($shoppingCartDto->userId, $shoppingCartDto->itemId);

        if ($shoppingCartDtoFromDb instanceof ShoppingCartSaveDTO) {
            $shoppingCartDto = new ShoppingCartSaveDTO(
                quantity: $shoppingCartDto->quantity,
                itemId: $shoppingCartDto->itemId,
                userId: $shoppingCartDto->userId,
                id: $shoppingCartDtoFromDb->id,
            );
        }

        $this->shoppingCartEntityManager->save($shoppingCartDto);
    }

    public function remove()
    {

    }

}