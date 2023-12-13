<?php

namespace App\Model\EntityManager;

use App\Entity\ShoppingCart;
use App\Model\Repository\ItemRepository;
use App\Model\Repository\ShoppingCartRepository;
use App\Model\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShoppingCartEntityManager
{
    public function manage(
        string                 $slug,
        Request                $request,
        UserRepository         $userRepository,
        SessionInterface       $session,
        EntityManagerInterface $entityManager,
        ShoppingCartRepository $cartRepository,
        ItemRepository         $itemRepository
    ): void
    {
        $userEmail = $session->get('user');
        $user = $userRepository->findOneByEmail($userEmail);
        if (!$user) {
            // Handle the case where no user is found
            return;
        }

        $itemId = $request->get('id');
        $cart = $cartRepository->findOneByUserIdAndItemId($user->getId(), $itemId);
        $itemToBeAdded = $itemRepository->findOneByItemId($itemId);

        if ($slug === 'add') {
            if ($cart === null && $itemToBeAdded) {
                $shoppingCart = new ShoppingCart();

                $shoppingCart->setItemId($itemId);
                $shoppingCart->setQuantity(1);
                $shoppingCart->setUserId($user->getId());
                $shoppingCart->setName($itemToBeAdded->getName());
                $shoppingCart->setPrice($itemToBeAdded->getPrice());
                $shoppingCart->setThumbnail($itemToBeAdded->getThumbnail());

                $entityManager->persist($shoppingCart);
            } elseif ($cart !== null) {
                $cart->setQuantity($cart->getQuantity() + 1);
                $entityManager->persist($cart);
            }
        } elseif ($slug === 'remove' && $cart !== null && $itemToBeAdded) {
            if ($cart->getQuantity() > 1) {
                $cart->setQuantity($cart->getQuantity() - 1);
                $entityManager->persist($cart);
            } elseif ($cart->getQuantity() === 1) {
                $entityManager->remove($cart);
            }
        }
        $entityManager->flush();
    }
}