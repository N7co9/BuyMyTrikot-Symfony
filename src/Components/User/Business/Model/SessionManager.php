<?php
declare(strict_types=1);

namespace App\Components\User\Business\Model;

use App\Global\DTO\OrderDTO;
use Symfony\Component\HttpFoundation\Request;

class SessionManager
{
    public function removeOrderFromSession(Request $request): void
    {
        $session = $request->getSession();
        $session->remove('orderFirstName');
        $session->remove('orderAddress');
        $session->remove('orderCity');
        $session->remove('orderState');
        $session->remove('orderZip');
        $session->remove('orderPhoneNumber');
        $session->remove('orderLastName');
        $session->remove('orderDeliveryMethod');
        $session->remove('orderShipping');
        $session->remove('orderDue');
    }

    public function addOrderToSession(OrderDTO $orderDto, Request $request): void
    {
        $session = $request->getSession();
        $session->set('orderFirstName', $orderDto->firstName);
        $session->set('orderAddress', $orderDto->address);
        $session->set('orderCity', $orderDto->city);
        $session->set('orderState', $orderDto->state);
        $session->set('orderZip', $orderDto->zip);
        $session->set('orderPhoneNumber', $orderDto->phoneNumber);
        $session->set('orderLastName', $orderDto->lastName);
        $session->set('orderDeliveryMethod', $orderDto->deliveryMethod);
        $session->set('orderShipping', $orderDto->shipping);
        $session->set('orderDue', $orderDto->due);
    }

    public function retrieveOrderFromSession(Request $request): OrderDTO
    {
        $session = $request->getSession();
        $orderDto = new OrderDTO();

        $orderDto->firstName = $session->get('orderFirstName');
        $orderDto->lastName = $session->get('orderLastName');
        $orderDto->address = $session->get('orderAddress');
        $orderDto->city = $session->get('orderCity');
        $orderDto->state = $session->get('orderState');
        $orderDto->zip = $session->get('orderZip');
        $orderDto->phoneNumber = $session->get('orderPhoneNumber');
        $orderDto->deliveryMethod = $session->get('orderDeliveryMethod');
        $orderDto->shipping = $session->get('orderShipping');
        $orderDto->due = $session->get('orderDue');

        return $orderDto;
    }

    public function addVerificationTokenToSession(Request $request, string $token): void
    {
        $session = $request->getSession();
        $session->set('verificationToken', $token);
    }

    public function retrieveVerificationTokenFromSession(Request $request): string
    {
        $session = $request->getSession();
        return $session->get('verificationToken');
    }

    public function addNewEmailToSession(Request $request): void
    {
        $session = $request->getSession();
        $session->set('unverifiedEmail', $request->get('email'));
    }

    public function retrieveUnverifiedEmailFromSession(Request $request): string
    {
        $session = $request->getSession();
        return $session->get('unverifiedEmail');
    }

}