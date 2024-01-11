<?php

namespace App\Components\Orderflow\Business;

use App\Components\Orderflow\Persistence\Mapper\Request2OrderDTO;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Orders;
use App\Entity\User;
use App\Global\Persistence\DTO\OrderDTO;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowBusinessFacade implements OrderFlowBusinessFacadeInterface
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly ShoppingCartRepository $cartRepository,
        private readonly OrderFlowEntityManager $flowEntityManager,
        private readonly OrdersRepository       $ordersRepository,
        private readonly ItemRepository         $itemRepository,
        private readonly Request2OrderDTO $request2OrderDTO
    )
    {
    }

    public function findOneByEmail(string $email): User
    {
        return $this->userRepository->findOneByEmail($email);
    }

    public function getItemsInCart(int $userID): array
    {
        return $this->cartRepository->findByUserId($userID);
    }

    public function getMostRecentOrder(string $email): ?Orders
    {
        return $this->ordersRepository->findMostRecentOrderByEmail($email);
    }

    public function findItemsByArrayOfIds(array $array): array
    {
        return $this->itemRepository->findItemsByArrayOfIds($array);
    }

    public function createOrder(OrderDTO $orderDto, UserDTO $userDto): void
    {
        $this->flowEntityManager->create($orderDto, $userDto);
    }

    public function mapRequestOrderToDto(Request $request): OrderDTO
    {
        return $this->request2OrderDTO->mapRequestOrderToDto($request);
    }
}