<?php

namespace App\Components\ShoppingCart\Persistence;

use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\Mapping\ShoppingCartMapping;
use App\Entity\ShoppingCart;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;


class ShoppingCartEntityManager
{
    public function __construct(
        public UserRepository               $userRepository,
        public EntityManagerInterface       $entityManager,
        public ShoppingCartRepository       $shoppingCartRepository,
        private readonly ShoppingCartMapping $shoppingCartMapping
    )
    {}



    public function save(ShoppingCartSaveDTO $shoppingCartDto)
    {
        $shoppingCartEntity = $this->shoppingCartRepository->find($shoppingCartDto->id);

        if($shoppingCartEntity === null) {
            $shoppingCartEntity = new ShoppingCart();
        }

        $shoppingCartEntity = $this->shoppingCartMapping->mapDtoToEntity($shoppingCartDto, $shoppingCartEntity);

        $this->insert($shoppingCartEntity);
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
        $shoppingCarts = $this->shoppingCartRepository->findByUserId($userId);
        foreach ($shoppingCarts as $shoppingCart) {
            $this->entityManager->remove($shoppingCart);
        }
        $this->entityManager->flush();
    }

    private function insert(ShoppingCart $cart) : void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
    private function remove(ShoppingCart $cart) : void
    {
        $this->shoppingCartRepository->createQueryBuilder('sc')
            ->delete()
            ->where('sc.id = :id')
            ->setParameter('id', $cart->getId())
            ->getQuery()
            ->execute();
    }
}
