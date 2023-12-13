<?php

namespace App\Model\EntityManager;

use App\Entity\Orders;
use App\Model\Repository\ShoppingCartRepository;
use App\Service\Validation\OrderFlowValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowEntityManager
{
    public array $errors = [];
    public function persistOrder(
        Request                $request,
        EntityManagerInterface $entityManager,
        OrderFlowValidation $validation,
        ShoppingCartRepository $cartRepository,
        string $email,
    ): ?array
    {
        if ($request->getMethod() === 'POST') {
            $order = new Orders();
            $order->setEmail($email);
            $order->setFirstName($request->get('first-name'));
            $order->setLastName($request->get('last-name'));
            $order->setAddress($request->get('address'));
            $order->setCity($request->get('city'));
            $order->setState($request->get('region'));
            $order->setZip($request->get('postal-code'));
            $order->setPhoneNumber($request->get('phone'));
            $order->setDeliveryMethod($request->get('delivery-method') ?? 'Standard');
            $order->setPaymentMethod($request->get('payment-type'));

            $order->setItems($cartRepository->findCartItemsByEmail($email));

            $order->setDue($request->get('totalCost'));

            $responseFromValidation = $validation->validate($order);
            $this->errors = array_filter($responseFromValidation, function ($response) {
                return $response !== null;
            });

            if (empty($this->errors)) {
                // No errors, proceed with persisting the order
                $entityManager->persist($order);
                $entityManager->flush();
                return null; // Explicitly return null to indicate success
            }
        }
        // Return the validation errors
        return $this->errors;
    }
}