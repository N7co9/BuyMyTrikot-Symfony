<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\Authentication\Persistence\ApiTokenRepository;
use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartExpensesDto;
use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartItemDto;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Entity\Items;
use App\Global\DTO\UserDTO;
use App\Global\Service\Items\ItemRepository;
use App\Global\Service\Mapping\UserMapper;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;

class ShoppingCartRead
{
    private const SHIPPING_COST = 4.95;

    public function __construct(
        private readonly ShoppingCartRepository      $shoppingCartRepository,
        private readonly ItemRepository              $itemRepository,
        private readonly UserBusinessFacadeInterface $userBusinessFacade
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
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
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

    public function fetchDeliveryMethod(Request $request): string
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        return $data['deliveryMethod'];
    }

    public function fetchShippingCost(Request $request): float
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        return $data['shippingCost'];
    }

    public function calculateExpenses(array $shoppingCartItemDtoList, string $deliveryMethod): ShoppingCartExpensesDto
    {
        $tax = 0;
        $subTotal = 0;
        $shippingCost = self::SHIPPING_COST;

        if ($deliveryMethod === 'Express') {
            $shippingCost = self::SHIPPING_COST + 11.05;
        }

        foreach ($shoppingCartItemDtoList as $shoppingCartItemDto) {
            $subTotal += $shoppingCartItemDto->price * $shoppingCartItemDto->quantity;
            $tax = $subTotal * 0.19;
        }

        return new ShoppingCartExpensesDto(
            tax: $tax,
            subTotal: $subTotal,
            shipping: $shippingCost,
            total: $subTotal + $tax + $shippingCost
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws \JsonException
     */
    public function fetchShoppingCartInformation(Request $request): ?array
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request);

        $deliveryMethod = $this->fetchDeliveryMethod($request);

        if ($userDTO) {
            $cart = $this->getCart($request);
            $expenses = $this->calculateExpenses($cart, $deliveryMethod);
        }

        return
            [
                'cart' => $cart ?? null,
                'expenses' => $expenses ?? null
            ];
    }
}