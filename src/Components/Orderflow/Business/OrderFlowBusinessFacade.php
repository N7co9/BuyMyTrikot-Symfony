<?php

namespace App\Components\Orderflow\Business;

use App\Components\Orderflow\Business\Model\OrderLogicHandling;
use App\Components\Orderflow\Communication\Mapping\Request2OrderDTO;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Components\User\Business\Model\SessionManager;
use App\Components\User\Persistence\UserRepository;
use App\Entity\Orders;
use App\Entity\User;
use App\Global\DTO\OrderDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Items\ItemRepository;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowBusinessFacade implements OrderFlowBusinessFacadeInterface
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly ShoppingCartRepository $cartRepository,
        private readonly OrdersRepository       $ordersRepository,
        private readonly ItemRepository         $itemRepository,
        private readonly Request2OrderDTO       $request2OrderDTO,
        private readonly OrderLogicHandling     $orderLogicHandling,
        private readonly SessionManager         $sessionManager,

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

    public function createOrder(OrderDTO $orderDto, UserDTO $userDto, Request $request): array
    {
        return $this->orderLogicHandling->createOrder($orderDto, $userDto);
    }

    public function mapRequestOrderToDto(Request $request): OrderDTO
    {
        return $this->request2OrderDTO->mapRequestOrderToDto($request);
    }

    public function validate(OrderDTO $orderDTO): array
    {
        return $this->orderLogicHandling->validate($orderDTO);
    }

    public function addOrderToSession(OrderDTO $orderDTO, Request $request): void
    {
        $this->sessionManager->addOrderToSession($orderDTO, $request);
    }

    public function retrieveOrderFromSession(Request $request): OrderDTO
    {
        return $this->sessionManager->retrieveOrderFromSession($request);
    }

    public function removeOrderFromSession(Request $request) : void
    {
        $this->sessionManager->removeOrderFromSession($request);
    }

}