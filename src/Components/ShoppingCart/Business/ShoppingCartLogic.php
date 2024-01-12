<?php

namespace App\Components\ShoppingCart\Business;

use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\ShoppingCart;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\SecurityBundle\Security;

class ShoppingCartLogic
{
    public function __construct(
        public UserRepository         $userRepository,
        public Security               $security,
        public EntityManagerInterface $entityManager,
        public ShoppingCartRepository $cartRepository,
        public ItemRepository         $itemRepository
    )
    {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function manage(string $slug, int $itemId): ?ShoppingCart
    {
        $userEmail = $this->security->getUser()?->getUserIdentifier();
        $user = $this->userRepository->findOneByEmail($userEmail);
        if (!$user) {
            throw new \RuntimeException('User not authenticated');
        }

        $cart = $this->cartRepository->findOneByUserIdAndItemId($user->getId(), $itemId);
        $itemToBeAdded = $this->itemRepository->findOneByItemId($itemId);

        if ($slug === 'add') {
            if ($cart === null && $itemToBeAdded) {
                $shoppingCart = new ShoppingCart();

                $shoppingCart->setItemId($itemId);
                $shoppingCart->setQuantity(1);
                $shoppingCart->setUserId($user->getId());
                $shoppingCart->setName($itemToBeAdded->getName() ?? '');
                $shoppingCart->setPrice($itemToBeAdded->getPrice() ?? 0.00);
                $shoppingCart->setThumbnail($itemToBeAdded->getThumbnail() ?? '');

                return $shoppingCart;
            }

            if ($cart !== null) {
                $cart->setQuantity($cart->getQuantity() + 1);
                return $cart;
            }
        } elseif ($slug === 'remove' && $cart !== null && $itemToBeAdded) {
            if ($cart->getQuantity() > 1) {
                $cart->setQuantity($cart->getQuantity() - 1);
                return $cart;
            }

            if ($cart->getQuantity() === 1) {
                $cart->setQuantity(0);
                return $cart;
            }
        }
        return null;
    }
    public function provideOrderCost($userId) : array
    {
        $itemsInCart = $this->cartRepository->findByUserId($userId);
        $subTotal = 0.00;
        foreach ($itemsInCart as $item)
        {
            $subTotal += $item->getPrice() * $item->getQuantity();
        }
        $tax = $subTotal * 0.19;
        $shipping = 4.95;
        $total = $subTotal + $shipping + $tax;
        return [
            'tax' => $tax,
            'shipping' => $shipping,
            'subTotal' => $subTotal,
            'total' => $total
        ];
    }
}