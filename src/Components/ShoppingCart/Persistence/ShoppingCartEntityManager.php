<?php

namespace App\Components\ShoppingCart\Persistence;

use App\Entity\ShoppingCart;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;


class ShoppingCartEntityManager
{
    public function __construct(
        public UserRepository $userRepository,
        public EntityManagerInterface $entityManager,
        public ShoppingCartRepository $cartRepository,
    )
    {}

    private function insert(ShoppingCart $cart) : void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    private function remove(ShoppingCart $cart) : void
    {
        $this->cartRepository->createQueryBuilder('sc')
            ->delete()
            ->where('sc.id = :id')
            ->setParameter('id', $cart->getId())
            ->getQuery()
            ->execute();
    }

    public function persist(ShoppingCart $cart) : void
    {
        if ($cart->getQuantity() === 0)
        {
            $this->remove($cart);
        }
        $this->insert($cart);
    }

    public function removeAllAfterSuccessfulOrder($userId): void
    {
        $shoppingCarts = $this->cartRepository->findByUserId($userId);
        foreach ($shoppingCarts as $shoppingCart) {
            $this->entityManager->remove($shoppingCart);
        }
        $this->entityManager->flush();
    }
}
