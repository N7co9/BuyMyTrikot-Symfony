<?php

namespace App\Components\Orderflow\Communication\Mapping;

use App\Global\DTO\OrderDTO;
use Symfony\Component\HttpFoundation\Request;

class Request2OrderDTO
{
    public function mapRequestOrderToDto(Request $request): OrderDTO
    {
        $orderDto = new OrderDTO();
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $orderDto->firstName = $data['firstName'];
        $orderDto->lastName = $data['lastName'];
        $orderDto->address = $data['address'];
        $orderDto->city = $data['city'];
        $orderDto->state = $data['region'];
        $orderDto->zip = $data['postalCode'];
        $orderDto->phoneNumber = $data['phoneNumber'];

        $orderDto->deliveryMethod = $data['deliveryMethod' ?? 'Standard'];

        $orderDto->shipping = $data['shippingCost'];
        $orderDto->due = $data['totalCost'];

        return $orderDto;
    }
}