<?php

namespace App\Components\ShoppingCart\Persistence;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\Mapping\ShoppingCartMapping;
use App\Components\User\Persistence\UserRepository;
use App\Entity\ShoppingCart;
use Doctrine\ORM\EntityManagerInterface;


class ShoppingCartEntityManager
{
    public function __construct(
        public UserRepository                $userRepository,
        public EntityManagerInterface        $entityManager,
        public ShoppingCartRepository        $shoppingCartRepository,
        private readonly ShoppingCartMapping $shoppingCartMapping
    )
    {
    }

    public function save(ShoppingCartSaveDTO $shoppingCartSaveDTO): void
    {
        $shoppingCartEntity = $this->shoppingCartRepository->find($shoppingCartSaveDTO->id);

        if ($shoppingCartEntity === null) {
            $shoppingCartEntity = new ShoppingCart();
        }

        $shoppingCartEntity = $this->shoppingCartMapping->mapDtoToEntity($shoppingCartSaveDTO, $shoppingCartEntity);

        $this->insert($shoppingCartEntity);
    }

    public function removeAllAfterSuccessfulOrder($userId): void
    {
        $shoppingCarts = $this->shoppingCartRepository->findShoppingCartEntitiesByUserId($userId);
        foreach ($shoppingCarts as $shoppingCart) {
            $this->entityManager->remove($shoppingCart);
        }
        $this->entityManager->flush();
    }

    private function insert(ShoppingCart $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function removeItemByItemId(int $itemId): void
    {
        $this->shoppingCartRepository->createQueryBuilder('sc')
            ->delete()
            ->where('sc.item_id = :item_id')
            ->setParameter('item_id', $itemId)
            ->getQuery()
            ->execute();

    }
}
