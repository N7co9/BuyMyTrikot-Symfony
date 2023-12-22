<?php

namespace App\Components\Orderflow\Business;

use App\Entity\Orders;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

interface OrderFlowBusinessFacadeInterface
{
    /**
     * Validate an order.
     *
     * @param Orders $order
     * @return array|null
     */
    public function validate(Orders $order): ?array;

    /**
     * Get the user identifier from Security.
     *
     * @param Security $security
     * @return string
     */
    public function getUserIdentifier(Security $security): string;

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User
     */
    public function findOneByEmail(string $email): User;

    /**
     * Get the user ID from Security.
     *
     * @param Security $security
     * @return int
     */
    public function getUserID(Security $security): int;

    /**
     * Get items in the user's cart.
     *
     * @param int $userID
     * @return array
     */
    public function getItemsInCart(int $userID): array;

    /**
     * Get the total cost of items in the cart.
     *
     * @param int $userID
     * @return array
     */
    public function getTotal(int $userID): array;

    /**
     * Get the most recent order for a user by email.
     *
     * @param string $email
     * @return Orders
     */
    public function getMostRecentOrder(string $email): ?Orders;

    /**
     * Find items by an array of IDs.
     *
     * @param array $array
     * @return array
     */
    public function findItemsByArrayOfIds(array $array): array;

    /**
     * Create an order flow.
     *
     * @param Request $request
     * @param string $email
     * @return array|null
     */
    public function createOrderFlow(Request $request, string $email): ?array;
}
