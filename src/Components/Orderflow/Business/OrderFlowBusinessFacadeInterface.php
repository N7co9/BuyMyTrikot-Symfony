<?php

namespace App\Components\Orderflow\Business;

use App\Entity\Orders;
use App\Entity\User;
use App\Global\Persistence\DTO\OrderDTO;
use App\Global\Persistence\DTO\UserDTO;

interface OrderFlowBusinessFacadeInterface
{
    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User
     */
    public function findOneByEmail(string $email): User;

    /**
     * Get items in the user's cart.
     *
     * @param int $userID
     * @return array
     */
    public function getItemsInCart(int $userID): array;

    /**
     * Get the most recent order for a user by email.
     *
     * @param string $email
     * @return ?Orders
     */
    public function getMostRecentOrder(string $email): ?Orders;

    /**
     * Find items by an array of IDs.
     *
     * @param array $array
     * @return array
     */
    public function findItemsByArrayOfIds(array $array): array;


    public function createOrder(OrderDTO $orderDto, UserDTO $userDto): array;


}
