<?php

namespace App\Tests\Components\ShoppingCart\Business;

use App\Components\Registration\Persistence\UserEntityManager;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacade;
use App\Components\ShoppingCart\Business\ShoppingCartLogic;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

    public function testManageCart(): void
    {
        $userRepo = $this->client->getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $res = $this->shoppingCartBusinessFacade->manageCart('add', 420);

        self::assertSame('', $res);



        // FINISH THIS SHIT
    }

    public function testFindOneByEmail(): void
    {
    }

    protected function tearDown() : void
    {
        $entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        parent::tearDown();
    }
}
