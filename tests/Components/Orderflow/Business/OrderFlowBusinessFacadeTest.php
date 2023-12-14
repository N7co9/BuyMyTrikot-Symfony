<?php

namespace App\Tests\Components\Orderflow\Business;
use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\Components\Orderflow\Business\Validation\OrderFlowValidation;
use App\Entity\Orders;
use App\Global\Persistence\DTO\ResponseDTO;
use PHPUnit\Framework\TestCase;

class OrderFlowBusinessFacadeTest extends TestCase
{
    private OrderFlowBusinessFacade $orderFlowBusinessFacade;

    protected function setUp(): void
    {
        $validation = $this->createMock(OrderFlowValidation::class);
        $this->orderFlowBusinessFacade = new OrderFlowBusinessFacade($validation);
    }

    public function testValidateWithValidOrder(): void
    {
        $order = new Orders();
        $order->setFirstName('John');
        $order->setLastName('Doe');
        $order->setCity('New York');
        $order->setZip('12345');
        $order->setAddress('123 Main St');
        $order->setState('NY');
        $order->setPhoneNumber('+1234567890');

        $validationResponse = [
            'firstName' => null,
            'lastName' => null,
            'city' => null,
            'zip' => null,
            'address' => null,
            'region' => null,
            'phoneNumber' => null,
        ];

        $validationMock = $this->createMock(OrderFlowValidation::class);
        $validationMock->expects(self::once())->method('validate')->willReturn($validationResponse);

        $orderFlowBusinessFacade = new OrderFlowBusinessFacade($validationMock);

        $result = $orderFlowBusinessFacade->validate($order);

        self::assertSame($validationResponse, $result);
    }

    public function testValidateWithInvalidFirstName(): void
    {
        $order = new Orders();
        $order->setFirstName(''); // Invalid empty first name

        $validationResponse = [
            'firstName' => new ResponseDTO('First name cannot be empty and must be a string.', 'Error'),
            'lastName' => null,
            'city' => null,
            'zip' => null,
            'address' => null,
            'region' => null,
            'phoneNumber' => null,
        ];

        $validationMock = $this->createMock(OrderFlowValidation::class);
        $validationMock->expects(self::once())->method('validate')->willReturn($validationResponse);

        $orderFlowBusinessFacade = new OrderFlowBusinessFacade($validationMock);

        $result = $orderFlowBusinessFacade->validate($order);

        self::assertSame($validationResponse, $result);
    }
}
