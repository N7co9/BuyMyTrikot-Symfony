<?php

namespace App\Tests\Components\Orderflow\Business;
use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\Components\Orderflow\Business\OrderFlowValidation;
use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\Orderflow\Persistence\OrdersRepository;
use App\Components\Registration\Persistence\UserEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Orders;
use App\Entity\User;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Repository\ItemRepository;
use App\Global\Persistence\Repository\UserRepository;
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

        $this->entityManager = $this->client->getContainer()->get(UserEntityManager::class);
        $userDTO = new UserDTO();
        $userDTO->email = 'test@lol.com';
        $userDTO->username = 'test';
        $userDTO->password = 'Xyz12345*';
        $this->entityManager->register($userDTO);


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
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $connection->executeQuery('DELETE FROM shopping_cart');
        $connection->executeQuery('ALTER TABLE shopping_cart AUTO_INCREMENT=0');

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        parent::tearDown();
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

        $this->assertSame($user->getId(), $result);
    }

    public function testFindOneByEmail()
    {
        $result = $this->orderFlowFacade->findOneByEmail('test@lol.com');
        self::assertInstanceOf(User::class, $result);
    }


}
