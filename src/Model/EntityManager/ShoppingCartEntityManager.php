<?php

namespace App\Model\EntityManager;

use App\Entity\ShoppingCart;
use Doctrine\ORM\EntityManagerInterface;

class ShoppingCartEntityManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function manage(string $slug)
    {
        if($slug === 'add'){
            $shoppingCart = new ShoppingCart();
            $this->entityManager->persist($shoppingCart);
            $this->entityManager->flush();
        }
    }
}