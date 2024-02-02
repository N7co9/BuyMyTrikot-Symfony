<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Communication\Mapper;

use App\Global\Persistence\DTO\OrderDTO;
use Symfony\Component\HttpFoundation\Request;

class OrderDTO2Session
{

    public function mapDtoToSession(OrderDTO $orderDto, Request $request): void
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
        $session->set('orderPaymentMethod', $orderDto->paymentMethod);
        $session->set('orderShipping', $orderDto->shipping);
        $session->set('orderDue', $orderDto->due);
    }
}
