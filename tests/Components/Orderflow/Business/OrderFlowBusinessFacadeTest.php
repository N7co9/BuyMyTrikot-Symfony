<?php

namespace App\Tests\Components\Orderflow\Business;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Entity\User;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\Components\Orderflow\Business\Validation\OrderFlowValidation;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Orders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class OrderFlowBusinessFacadeTest extends WebTestCase
{
    private $validation;
    private $userRepository;
    private $cartRepository;
    private $flowEntityManager;
    private $entityManager;
    private $cartEntityManager;
    private $ordersRepository;
    private $itemRepository;
    private $security;

    private $orderFlowFacade;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->validation = $this->createMock(OrderFlowValidation::class);
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->cartRepository = $this->createMock(ShoppingCartRepository::class);
        $this->flowEntityManager = $this->createMock(OrderFlowEntityManager::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->cartEntityManager = $this->createMock(ShoppingCartEntityManager::class);
        $this->ordersRepository = $this->createMock(OrdersRepository::class);
        $this->itemRepository = $this->createMock(ItemRepository::class);

        $this->orderFlowFacade = new OrderFlowBusinessFacade(
            $this->validation,
            $this->userRepository,
            $this->cartRepository,
            $this->flowEntityManager,
            $this->entityManager,
            $this->cartEntityManager,
            $this->ordersRepository,
            $this->itemRepository
        );

        $this->security = $this->createMock(Security::class);
    }

    public function testValidateValidOrder()
    {
        // Arrange
        $order = new Orders(); // Create a valid Orders entity
        $this->validation->expects($this->once())
            ->method('validate')
            ->willReturn(null);

        // Act
        $result = $this->orderFlowFacade->validate($order);

        // Assert
        $this->assertNull($result);
    }

    public function testValidateInvalidOrder()
    {
        // Arrange
        $order = new Orders(); // Create an invalid Orders entity
        $this->validation->expects($this->once())
            ->method('validate')
            ->willReturn(['Validation error']);

        // Act
        $result = $this->orderFlowFacade->validate($order);

        // Assert
        $this->assertEquals(['Validation error'], $result);
    }

    public function testGetUserIdentifier()
    {
        $ur = self::getContainer()->get(UserRepository::class);
        $user = $ur->findOneByEmail('test@lol.com');

        $this->client->loginUser($user);

        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);


        $result = $this->orderFlowFacade->getUserIdentifier($this->security);
        $this->assertEquals('test@lol.com', $result);
    }

    public function testGetUserID()
    {
        $ur = self::getContainer()->get(UserRepository::class);
        $user = $ur->findOneByEmail('test@lol.com');

        $this->client->loginUser($user);

        $this->security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $result = $this->orderFlowFacade->getUserID($this->security);

        $this->assertEquals(1, $result);
    }

    public function testFindOneByEmail()
    {
        $result = $this->orderFlowFacade->findOneByEmail('test@lol.com');
        self::assertInstanceOf(User::class, $result);
    }
}
