<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business\Model;

use App\Components\Orderflow\Communication\Form\OrderBillingValidation;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Global\DTO\OrderDTO;
use App\Global\DTO\UserDTO;

class OrderLogicHandling
{
    public function __construct
    (
        private readonly OrderFlowEntityManager $orderFlowEntityManager,
        private readonly OrderBillingValidation $orderFlowValidation,
    )
    {
    }

    public function createOrder(OrderDTO $orderDTO, UserDTO $userDTO): array
    {
        $errors = $this->validate($orderDTO);

        if (empty($errors)) {
            $orderDTO->email = $userDTO->email;
            $this->orderFlowEntityManager->create($orderDTO, $userDTO);
        }
        return $errors;
    }

    public function validate(OrderDTO $orderDTO): array
    {
        return $this->orderFlowValidation->validate($orderDTO);
    }
}