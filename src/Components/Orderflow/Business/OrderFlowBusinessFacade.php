<?php

namespace App\Components\Orderflow\Business;

use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Orders;
use App\Entity\User;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowBusinessFacade implements OrderFlowBusinessFacadeInterface
{
    public function __construct(
        public OrderFlowValidation $validation,
        public UserRepository $userRepository,
        public ShoppingCartRepository $cartRepository,
        public OrderFlowEntityManager $flowEntityManager,
        public EntityManagerInterface $entityManager,
        public ShoppingCartEntityManager $cartEntityManager,
        public OrdersRepository $ordersRepository,
        public ItemRepository $itemRepository
    )
    {}

    public function validate(Orders $order) : ?array
    {
        return $this->validation->validate($order);
    }

    public function getUserIdentifier(Security $security): string
    {
        return $security->getUser()?->getUserIdentifier();
    }

    public function findOneByEmail(string $email) : User
    {
        return $this->userRepository->findOneByEmail($email);
    }

    public function getUserID(Security $security) : int
    {
        return $security->getUser()?->getId();
    }

    public function getItemsInCart(int $userID) : array
    {
        return $this->cartRepository->findByUserId($userID);
    }

    public function getTotal(int $userID) : array
    {
        return $this->cartRepository->getTotal($userID);
    }

    public function getMostRecentOrder(string $email) : ?Orders
    {
        return $this->ordersRepository->findMostRecentOrderByEmail($email);
    }

    public function findItemsByArrayOfIds(array $array) : array
    {
        return $this->itemRepository->findItemsByArrayOfIds($array);
    }

    public function createOrderFlow(Request $request, string $email) : ?array
    {
        return $this->flowEntityManager->persistOrder($request, $this->entityManager, $this->validation, $this->cartRepository, $this->cartEntityManager, $this->userRepository, $email);
    }
}
