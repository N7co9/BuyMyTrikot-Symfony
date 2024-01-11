<?php

namespace App\Components\Orderflow\Persistence\Mapper;

use App\Entity\Orders;
use App\Global\Persistence\DTO\OrderDTO;

class OrderDTO2OrderEntity
{

    public function map(OrderDTO $orderDto): Orders
    {
        $order = new Orders();
        $order->setemail($orderDto->email);
        $order->setfirstName($orderDto->firstName);
        $order->setlastName($orderDto->lastName);
        $order->setaddress($orderDto->address);
        $order->setcity($orderDto->city);
        $order->setstate($orderDto->state);
        $order->setzip($orderDto->zip);
        $order->setphoneNumber($orderDto->phoneNumber);
        $order->setdeliveryMethod($orderDto->deliveryMethod);
        $order->setpaymentMethod($orderDto->paymentMethod);
        $order->setdue($orderDto->due);

        return $order;
    }
}