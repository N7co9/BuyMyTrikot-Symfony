<?php

namespace App\Tests\Model\EntityManager;

use App\Components\Orderflow\Business\Validation\OrderFlowValidation;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Orders;
use App\Global\Persistence\DTO\ResponseDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class OrderFlowEntityManagerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private OrderFlowValidation $validation;
    private ShoppingCartRepository $cartRepository;
    private OrdersRepository $ordersRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Boot the Symfony kernel
        self::bootKernel();

        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();

        // Create mock objects for dependencies
        $this->validation = $this->createMock(OrderFlowValidation::class);
        $this->cartRepository = $this->createMock(ShoppingCartRepository::class);
        $this->ordersRepository = $this->entityManager->getRepository(Orders::class);
    }

    public function testPersistOrderWithValidData(): void
    {
        // Create a request object with valid data
        $request = new Request([], [
            'first-name' => 'John',
            'last-name' => 'Doe',
            'address' => '123 Main St',
            'city' => 'City',
            'region' => 'Region',
            'postal-code' => '12345',
            'phone' => '1234567890',
            'delivery-method' => 'Standard',
            'payment-type' => 'Credit Card',
            'totalCost' => 100.00,
        ]);

        $request->setMethod('POST');

        // Set up the validation mock to return an array with errors
        $this->validation->expects($this->once())
            ->method('validate')
            ->willReturn([]);

        $orderEntityManager = new OrderFlowEntityManager();

        $result = $orderEntityManager->persistOrder($request, $this->entityManager, $this->validation, $this->cartRepository, 'valid@valid.com');

        $savedOrder = $this->ordersRepository->findMostRecentOrderByEmail('valid@valid.com');

        self::assertNull($result);

        self::assertSame('John', $savedOrder->getFirstName());
        self::assertSame('Doe', $savedOrder->getLastName());
    }

    public function testPersistOrderWithInvalidData(): void
    {
        // Create a request object with valid data
        $request = new Request([], [
            'first-name' => 'John',
            'last-name' => 'Doe',
            'address' => '123 Main St',
            'city' => '*****',
            'region' => 'Region',
            'postal-code' => '12345',
            'phone' => '1234567890',
            'delivery-method' => 'Standard',
            'payment-type' => 'Credit Card',
            'totalCost' => 100.00,
        ]);

        $request->setMethod('POST');

        // Set up the validation mock to return an array with errors
        $this->validation->expects($this->once())
            ->method('validate')
            ->willReturn(['city' => new ResponseDTO('Error', 'Error')]);

        $orderEntityManager = new OrderFlowEntityManager();

        $result = $orderEntityManager->persistOrder($request, $this->entityManager, $this->validation, $this->cartRepository, 'valid@valid.com');

        $savedOrder = $this->ordersRepository->findMostRecentOrderByEmail('valid@valid.com');

        self::assertInstanceOf(ResponseDTO::class, $result['city']);
        self::assertNull($savedOrder);
    }

    protected function tearDown(): void
    {
        // Clear the data from the 'orders' table
        $this->entityManager->createQuery('DELETE FROM App\Entity\Orders')->execute();

        // Clear the data from the 'shopping_cart' table if needed
        // $this->entityManager->createQuery('DELETE FROM App\Entity\ShoppingCart')->execute();

        // Add additional DELETE queries for other tables as needed

        // Ensure that the EntityManager is closed after tests to avoid memory leaks
        $this->entityManager->close();
        parent::tearDown();
    }
}
