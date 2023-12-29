<?php

namespace App\Tests\Components\Orderflow\Business;

use App\Components\Orderflow\Business\OrderFlowValidation;
use App\Entity\Orders;
use App\Global\Persistence\DTO\ResponseDTO;
use PHPUnit\Framework\TestCase;

class OrderFlowValidationTest extends TestCase
{
    private OrderFlowValidation $orderFlowValidation;

    protected function setUp(): void
    {
        $this->orderFlowValidation = new OrderFlowValidation();
    }

    public function testValidate()
    {
        $order = new Orders();

        // Test a valid order
        $order->setFirstName('John');
        $order->setLastName('Doe');
        $order->setCity('City');
        $order->setState('State');
        $order->setPhoneNumber('+1234567890');
        $order->setZip('12345');
        $order->setAddress('123 Main St.');

        $responses = $this->orderFlowValidation->validate($order);
        $this->assertCount(7, $responses);
        foreach ($responses as $response) {
            $this->assertNull($response);
        }

        // Test an order with invalid fields
        $order->setFirstName('A');
        $order->setLastName('123');
        $order->setCity('12345');
        $order->setState('12345');
        $order->setPhoneNumber('InvalidPhoneNumber');
        $order->setZip('InvalidZip');
        $order->setAddress('Address with special characters *&^');

        $responses = $this->orderFlowValidation->validate($order);
        $this->assertCount(7, $responses);
        foreach ($responses as $response) {
            $this->assertInstanceOf(ResponseDTO::class, $response);
            $this->assertEquals('Error', $response->getType());
        }
    }
    public function testValidateEdgeCases()
    {
        $order = new Orders();

        // Test an empty order (all fields empty)
        $responses = $this->orderFlowValidation->validate($order);
        $this->assertCount(7, $responses);
        foreach ($responses as $response) {
            $this->assertInstanceOf(ResponseDTO::class, $response);
            $this->assertEquals('Error', $response->getType());
        }

        // Test a valid order with minimum field lengths
        $order->setFirstName('Jon');
        $order->setLastName('Doe');
        $order->setCity('Cas');
        $order->setState('Sod');
        $order->setPhoneNumber('+1234567890');
        $order->setZip('1234');
        $order->setAddress('A1f');

        $responses = $this->orderFlowValidation->validate($order);
        $this->assertCount(7, $responses);
        foreach ($responses as $response) {
            $this->assertNull($response);
        }

        // Test a valid order with maximum field lengths
        $order->setFirstName(str_repeat('A', 29));
        $order->setLastName(str_repeat('B', 29));
        $order->setCity(str_repeat('C', 19));
        $order->setState(str_repeat('D', 19));
        $order->setPhoneNumber('+1234567890123456');
        $order->setZip('123456');
        $order->setAddress(str_repeat('E', 19));

        $responses = $this->orderFlowValidation->validate($order);
        $this->assertCount(7, $responses);
        foreach ($responses as $response) {
            $this->assertNull($response);
        }
    }
}
