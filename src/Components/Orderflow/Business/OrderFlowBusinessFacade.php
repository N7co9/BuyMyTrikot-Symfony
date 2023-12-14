<?php

namespace App\Components\Orderflow\Business;

use App\Components\Orderflow\Business\Validation\OrderFlowValidation;
use App\Entity\Orders;

class OrderFlowBusinessFacade
{
    public function __construct(
        public OrderFlowValidation $validation
    )
    {}

    public function validate(Orders $order) : ?array
    {
        return $this->validation->validate($order);
    }
}
