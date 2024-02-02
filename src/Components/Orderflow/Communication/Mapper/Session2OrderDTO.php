<?php
declare(strict_types=1);

namespace App\Components\Orderflow\Communication\Mapper;

use App\Global\Persistence\DTO\OrderDTO;
use Symfony\Component\HttpFoundation\Request;

class Session2OrderDTO
{

    public function mapSessionToDto(Request $request): OrderDTO
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
        $orderDto->paymentMethod = $session->get('orderPaymentMethod');
        $orderDto->shipping = $session->get('orderShipping');
        $orderDto->due = $session->get('orderDue');

        return $orderDto;
    }
}
