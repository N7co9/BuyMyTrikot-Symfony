<?php

namespace App\Components\Orderflow\Communication\Mapper;

use App\Global\Persistence\DTO\OrderDTO;
use Symfony\Component\HttpFoundation\Request;

class Request2OrderDTO
{
    public function mapRequestOrderToDto(Request $request): OrderDTO
    {
        $orderDto = new OrderDTO();

        $orderDto->firstName = $request->get('first-name');
        $orderDto->lastName = $request->get('last-name');
        $orderDto->address = $request->get('address');
        $orderDto->city = $request->get('city');
        $orderDto->state = $request->get('region');
        $orderDto->zip = $request->get('postal-code');
        $orderDto->phoneNumber = $request->get('phone');
        $orderDto->deliveryMethod = $request->get('delivery-method', 'Standard');
        $orderDto->paymentMethod = $request->get('payment-type');
        $orderDto->shipping = $request->get('shippingCost');
        $orderDto->due = $request->get('totalCost');

        return $orderDto;
    }
}