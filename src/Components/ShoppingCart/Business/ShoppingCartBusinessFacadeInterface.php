<?php

namespace App\Components\ShoppingCart\Business;

use App\Entity\ShoppingCart;
use Symfony\Bundle\SecurityBundle\Security;

interface ShoppingCartBusinessFacadeInterface
{
    /**
     * Manage the shopping cart based on a slug and item ID.
     *
     * @param string $slug
     * @param int $itemId
     * @return ShoppingCart|null
     */
    public function manageCart(string $slug, int $itemId): ?ShoppingCart;

    /**
     * Get items for a specific user.
     *
     * @param int $userID
     */
    public function getItems(int $userID): array;

    /**
     * Get the total cost for a specific user's cart.
     *
     * @param int $userID
     * @return array
     */
    public function getTotal(int $userID): array;
}
