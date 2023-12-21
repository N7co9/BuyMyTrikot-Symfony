<?php

namespace App\Components\ShoppingCart\Persistence;

use App\Entity\ShoppingCart;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShoppingCartEntityManager
{

    public function manage(
        string                 $slug,
        Request                $request,
        UserRepository         $userRepository,
        Security               $security,
        EntityManagerInterface $entityManager,
        ShoppingCartRepository $cartRepository,
        ItemRepository         $itemRepository
    ): void
    {
        $userEmail = $security->getUser()->getUserIdentifier();
        $user = $userRepository->findOneByEmail($userEmail);
        if (!$user) {
            throw new \RuntimeException('User not authenticated');
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
                $shoppingCart->setName($itemToBeAdded->getName() ?? '');
                $shoppingCart->setPrice($itemToBeAdded->getPrice() ?? 0.00);
                $shoppingCart->setThumbnail($itemToBeAdded->getThumbnail() ?? '');

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
    public function removeAllItemsFromUser(string $email, EntityManagerInterface $entityManager, ShoppingCartRepository $cartRepository, UserRepository $userRepository): void
    {
        $userId = $userRepository->findOneByEmail($email);
        $shoppingCarts = $cartRepository->findByUserId($userId->getId());

        foreach ($shoppingCarts as $shoppingCart) {
            $entityManager->remove($shoppingCart);
        }

        $entityManager->flush();
    }
}
