<?php

namespace App\Tests\Components\ShoppingCart\Business;

use App\Components\Registration\Persistence\UserEntityManager;
use App\Components\ShoppingCart\Business\Model\ShoppingCartLogic;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacade;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Components\User\Persistence\UserRepository;
use App\Entity\ShoppingCart;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class ShoppingCartBusinessFacadeTest extends WebTestCase
{
    private ShoppingCartBusinessFacade $shoppingCartBusinessFacade;
    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->entityManager = $this->client->getContainer()->get(UserEntityManager::class);
        $userDTO = new UserDTO();
        $userDTO->email = 'test@lol.com';
        $this->entityManager->register($userDTO);

        $this->security = $this->client->getContainer()->get(Security::class);
        $this->ShoppingCartLogic = $this->client->getContainer()->get(ShoppingCartLogic::class);
        $this->ShoppingCartRepository = $this->createMock(ShoppingCartRepository::class);
        $this->ShoppingCartEntityManager = $this->createMock(ShoppingCartEntityManager::class);

        $this->shoppingCartBusinessFacade = new ShoppingCartBusinessFacade($this->ShoppingCartLogic, $this->ShoppingCartRepository, $this->ShoppingCartEntityManager);
        parent::setUp();
    }

    public function testManageCartUserNotAuthenticated(): void
    {
        $this->expectExceptionMessage('User not authenticated');
        $this->shoppingCartBusinessFacade->manageCart('add', 44);
    }

    public function testManageCartAdd(): void
    {
        $userRepo = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $res = $this->shoppingCartBusinessFacade->manageCart('add', 420);

        self::assertSame('test', $res->getName());
    }

    public function testManageCartAddOnTopException() : void
    {
        $userRepo = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $SCEM = new ShoppingCartEntityManager($this->client->getContainer()->get(UserRepository::class),$this->client->getContainer()->get(EntityManagerInterface::class),$this->client->getContainer()->get(ShoppingCartRepository::class));

        $cartItem = new ShoppingCart();
        $cartItem->setItemId(420);
        $cartItem->setName('Test');
        $cartItem->setPrice(10.99);
        $cartItem->setQuantity(1);
        $cartItem->setThumbnail('test');
        $cartItem->setUserId(3);

        $SCEM->persist($cartItem);

        $res = $this->shoppingCartBusinessFacade->manageCart('add', 420);
        self::assertSame(2, $res->getQuantity());
    }

    public function testManageCartRemove() : void
    {
        $userRepo = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $SCEM = new ShoppingCartEntityManager($this->client->getContainer()->get(UserRepository::class),$this->client->getContainer()->get(EntityManagerInterface::class),$this->client->getContainer()->get(ShoppingCartRepository::class));

        $cartItem = new ShoppingCart();
        $cartItem->setItemId(420);
        $cartItem->setName('Test');
        $cartItem->setPrice(10.99);
        $cartItem->setQuantity(4);
        $cartItem->setThumbnail('test');
        $cartItem->setUserId(3);

        $SCEM->persist($cartItem);

        $res = $this->shoppingCartBusinessFacade->manageCart('remove', 420);
        self::assertSame(3, $res->getQuantity());
    }
    public function testManageCartRemoveFromExistingException() : void
    {
        $userRepo = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $SCEM = new ShoppingCartEntityManager($this->client->getContainer()->get(UserRepository::class),$this->client->getContainer()->get(EntityManagerInterface::class),$this->client->getContainer()->get(ShoppingCartRepository::class));

        $cartItem = new ShoppingCart();
        $cartItem->setItemId(420);
        $cartItem->setName('Test');
        $cartItem->setPrice(10.99);
        $cartItem->setQuantity(1);
        $cartItem->setThumbnail('test');
        $cartItem->setUserId(3);

        $SCEM->persist($cartItem);

        $res = $this->shoppingCartBusinessFacade->manageCart('remove', 420);
        self::assertSame(0, $res->getQuantity());
    }

    public function testBadSlug() : void
    {
        $userRepo = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $res = $this->shoppingCartBusinessFacade->manageCart('BAD', 420);

        self::assertNull($res);
    }

    public function testPersist() : void
    {
        $cartItem = new ShoppingCart();
        $cartItem->setItemId(420);
        $cartItem->setName('Test');
        $cartItem->setPrice(10.99);
        $cartItem->setQuantity(1);
        $cartItem->setThumbnail('test');
        $cartItem->setUserId(3);

        $ShoppingCartLogic = $this->client->getContainer()->get(ShoppingCartLogic::class);
        $ShoppingCartRepository = $this->client->getContainer()->get(ShoppingCartRepository::class);
        $ShoppingCartEntityManager = $this->client->getContainer()->get(ShoppingCartEntityManager::class);

        $ShoppingCartBusinessFacade = new ShoppingCartBusinessFacade($ShoppingCartLogic, $ShoppingCartRepository, $ShoppingCartEntityManager);

        $ShoppingCartBusinessFacade->persist($cartItem);

        $res = $ShoppingCartRepository->findByUserId(3);
        self::assertSame('Test', $res[0]->getName());
    }

    protected function tearDown() : void
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
}
