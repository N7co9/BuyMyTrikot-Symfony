<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Business\Model;

use App\Components\Orderflow\Communication\Form\OrderBillingValidation;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\ShoppingCart\Business\Model\ShoppingCartRead;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowWrite
{

    public function __construct
    (
        public readonly ShoppingCartRead       $shoppingCartRead,
        public readonly OrderFlowRead          $orderFlowRead,
        public readonly OrderFlowEntityManager $entityManager,
        public readonly OrderBillingValidation $billingValidation,
        public readonly EntityManagerInterface $entityManagerInterface
    )
    {
    }

    public function persistOrder(Request $request): ?array
    {
        $userDTO = $this->shoppingCartRead->getUser($request);
        $orderDTO = $this->orderFlowRead->buildOrderDTO($request);

        $errors = $this->billingValidation->validate($orderDTO);

        if (!$errors) {
            $this->entityManager->create($orderDTO, $userDTO);
            return ['response' => 'OK'];
        }
        return $errors;
    }

    public function removeOrder(Request $request): void
    {
        $order = $this->orderFlowRead->fetchMostRecentOrder($request);

        $this->entityManagerInterface->remove($order);
        $this->entityManagerInterface->flush();
    }

}