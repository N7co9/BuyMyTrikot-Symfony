<?php

namespace App\Components\Orderflow\Persistence;

use App\Components\Orderflow\Persistence\Mapper\OrderDTO2OrderEntity;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Global\DTO\OrderDTO;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;

class OrderFlowEntityManager
{
    public function __construct(
        private readonly EntityManagerInterface    $entityManager,
        private readonly OrderDTO2OrderEntity      $orderMapper,
    )
    {
    }

    public function create(OrderDTO $orderDto, UserDTO $userDto): void
    {
        $order = $this->orderMapper->map($orderDto);


        $this->entityManager->persist($order);
        $this->entityManager->flush();

      //  $this->shoppingCartEntityManager->removeAllAfterSuccessfulOrder($userDto->id);
    }
}