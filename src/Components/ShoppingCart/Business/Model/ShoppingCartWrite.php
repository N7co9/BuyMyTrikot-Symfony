<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Components\User\Business\UserBusinessFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class ShoppingCartWrite
{
    public function __construct(
        private readonly UserBusinessFacadeInterface $userBusinessFacade,
        private readonly ShoppingCartRepository    $shoppingCartRepository,
        private readonly ShoppingCartEntityManager $shoppingCartEntityManager,
    )
    {
    }

    public function buildShoppingCartSaveDTO(Request $request): ShoppingCartSaveDTO
    {
        $userId = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content->id;

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

        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
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
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $itemId = $data['itemId'];

        $this->shoppingCartEntityManager->removeItemByItemId($itemId, $userDTO);
    }

    public function removeAllAfterOrderSuccess(Request $request): void
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
        $this->shoppingCartEntityManager->removeAllAfterSuccessfulOrder($userDTO->id);
    }
}