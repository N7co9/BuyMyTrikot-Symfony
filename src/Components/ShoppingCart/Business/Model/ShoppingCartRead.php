<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartItemDto;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Items;
use App\Global\DTO\UserDTO;
use App\Global\Service\Items\ItemRepository;
use App\Global\Service\Mapping\UserMapper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;

class ShoppingCartRead
{
    public function __construct(
        private readonly ShoppingCartRepository $shoppingCartRepository,
        private readonly ItemRepository         $itemRepository,
        private readonly UserMapper             $userMapper,
        private readonly ApiTokenRepository     $apiTokenRepository,
    )
    {
    }

    /**
     *
     * @return ShoppingCartItemDto[]
     * @throws NonUniqueResultException
     *
     */
    public function getCart(Request $request): array
    {
        $userDTO = $this->getUser($request);
        $shoppingCartSaveDTOArray = $this->shoppingCartRepository->findByUserId($userDTO->id);
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

    public function getUser(Request $request): UserDTO
    {
        return $this->userMapper->mapEntityToDto($this->apiTokenRepository->findUserByToken($request->headers->get('Authorization')));
    }
}