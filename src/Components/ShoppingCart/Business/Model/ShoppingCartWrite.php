<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use Symfony\Component\HttpFoundation\Request;

class ShoppingCartWrite implements CartWriteInterface
{
    public function __construct(
        private readonly ShoppingCartRepository    $shoppingCartRepository,
        private readonly ShoppingCartEntityManager $shoppingCartEntityManager,
        private readonly ShoppingCartRead          $shoppingCartRead,
    )
    {
    }

    public function buildShoppingCartSaveDTO(Request $request): ShoppingCartSaveDTO
    {
        $userId = $this->shoppingCartRead->getUser($request)->id;

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $itemId = $data['itemId'];
        $quantity = $data['quantity'];

        return new ShoppingCartSaveDTO
        (
            quantity: $quantity ?? 1,
            itemId: $itemId,
            userId: $userId,
        );
    }

    public function save(Request $request): void
    {
        $shoppingCartDto = $this->buildShoppingCartSaveDTO($request);

        $userDTO = $this->shoppingCartRead->getUser($request);
        $shoppingCartDto->id = $userDTO->id;
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

    public function remove(Request $request): void
    {
        $userDTO = $this->shoppingCartRead->getUser($request);

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $itemId = $data['itemId'];

        $this->shoppingCartEntityManager->removeItemByItemId($itemId, $userDTO);
    }

    public function removeAllAfterOrderSuccess(Request $request) : void
    {
        $userDTO = $this->shoppingCartRead->getUser($request);
        $this->shoppingCartEntityManager->removeAllAfterSuccessfulOrder($userDTO->id);
    }
}