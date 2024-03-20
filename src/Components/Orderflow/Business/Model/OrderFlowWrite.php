<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business\Model;

use App\Components\Orderflow\Communication\Form\OrderBillingValidation;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\ShoppingCart\Business\Model\ShoppingCartRead;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Global\DTO\ResponseDTO;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowWrite
{

    public function __construct
    (
        private readonly UserBusinessFacadeInterface $userBusinessFacade,
        public readonly ShoppingCartRead             $shoppingCartRead,
        public readonly OrderFlowRead                $orderFlowRead,
        public readonly OrderFlowEntityManager       $entityManager,
        public readonly OrderBillingValidation       $billingValidation,
    )
    {
    }

    public function persistOrder(Request $request): ResponseDTO
    {
        $userDTO = $this->userBusinessFacade->fetchUserInformationFromAuthentication($request)->content;
        $orderDTO = $this->orderFlowRead->buildOrderDTO($request)->content;

        $errors = $this->billingValidation->validate($orderDTO);

        if (!$errors) {
            $this->entityManager->create($orderDTO, $userDTO);
            return new ResponseDTO($errors, true);
        }

        return new ResponseDTO($errors, false);
    }

    public function removeMostRecentOrder(Request $request): void
    {
        $order = $this->orderFlowRead->fetchMostRecentOrder($request);
        $this->entityManager->removeMostRecentOrder($order);
    }

    public function setOrderSuccessful(Request $request): void
    {
        $order = $this->orderFlowRead->fetchMostRecentOrder($request);
        $this->entityManager->setOrderSuccessful($order);
    }

}