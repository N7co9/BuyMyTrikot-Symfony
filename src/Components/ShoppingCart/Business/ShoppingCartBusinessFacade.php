<?php

namespace App\Components\ShoppingCart\Business;

namespace App\Components\ShoppingCart\Business;

use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\ShoppingCart;
use Doctrine\ORM\NonUniqueResultException;


class ShoppingCartBusinessFacade implements ShoppingCartBusinessFacadeInterface
{

    public function __construct(
        private readonly ShoppingCartLogic         $shoppingCartLogic,
        private readonly ShoppingCartRepository    $cartRepository,
        private readonly ShoppingCartEntityManager $entityManager)
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function manageCart(string $slug, int $itemId): ?ShoppingCart
    {
        return $this->shoppingCartLogic->manage($slug, $itemId);
    }

    public function getItems(int $userID): array
    {
        return $this->cartRepository->findByUserId($userID);
    }

    public function getTotal(int $userID): array
    {
        return $this->shoppingCartLogic->provideOrderCost($userID);
    }

    public function persist(ShoppingCart $cart): void
    {
        $this->entityManager->persist($cart);
    }

}
