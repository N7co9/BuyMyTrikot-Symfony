<?php

namespace App\Components\ShoppingCart\Business;

namespace App\Components\ShoppingCart\Business;

use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Items;
use App\Entity\ShoppingCart;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;


class ShoppingCartBusinessFacade implements ShoppingCartBusinessFacadeInterface
{
    private ShoppingCartLogic $shoppingCartLogic;

    public function __construct(
        ShoppingCartLogic $shoppingCartLogic,
        private ShoppingCartRepository $cartRepository,
        private ShoppingCartEntityManager $entityManager)
    {
        $this->shoppingCartLogic = $shoppingCartLogic;
    }

    public function manageCart(string $slug, int $itemId): ?ShoppingCart
    {
        return $this->shoppingCartLogic->manage($slug, $itemId);
    }

    public function findOneByEmail(Security $security): ?int
    {
        $user = $security->getUser();

        if ($user instanceof User) {
            return $user->getId();
        }
        return null;
    }

    public function getItems(int $userID): array
    {
        return $this->cartRepository->findByUserId($userID);
    }

    public function getTotal(int $userID) : array
    {
        return $this->cartRepository->getTotal($userID);
    }

    public function persist(ShoppingCart $cart) : void
    {
        $this->entityManager->persist($cart);
    }

}
