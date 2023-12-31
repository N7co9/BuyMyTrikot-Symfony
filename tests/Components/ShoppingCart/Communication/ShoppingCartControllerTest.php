<?php

namespace App\Tests\Components\ShoppingCart\Communication;
namespace App\Tests\Components\ShoppingCart\Communication;


use App\Components\Registration\Persistence\UserEntityManager;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacade;
use App\Components\ShoppingCart\Communication\ShoppingCartController;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\ShoppingCart;
use App\Global\Persistence\DTO\UserDTO;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShoppingCartControllerTest extends WebTestCase
{
    public ShoppingCartController $cartController;
    public SessionInterface $session;
    public UserRepository $userRepository;
    public ShoppingCartRepository $cartRepository;
    public KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        parent::setUp();
    }

    public function testIndexNoUser(): void
    {

        // Send a GET request to the shopping cart route
        $this->client->request('GET', '/shopping/cart');
        self::assertStringContainsString('(500 Internal Server Error)', $this->client->getResponse()->getContent());
    }

    public function testIndexUserLoggedInNoItemsInCart(): void
    {
        $entityManager = $this->client->getContainer()->get(UserEntityManager::class);
        $userDTO = new UserDTO();
        $userDTO->email = 'test@lol.com';
        $userDTO->username = 'test';
        $userDTO->password = 'Xyz12345*';
        $entityManager->register($userDTO);


        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@lol.com');

        $this->client->loginUser($testUser);

        $this->client->request('GET', '/shopping/cart');


        self::assertStringNotContainsString('(500 Internal Server Error)', $this->client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
    }

    public function testManage(): void
    {
        $entityManager = $this->client->getContainer()->get(UserEntityManager::class);
        $userDTO = new UserDTO();
        $userDTO->email = 'test@lol.com';
        $userDTO->username = 'test';
        $userDTO->password = 'Xyz12345*';
        $entityManager->register($userDTO);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@lol.com');

        $cart = new ShoppingCart();

        $shoppingCartBusiness = $this->createMock(ShoppingCartBusinessFacade::class);
        $shoppingCartBusiness->method('manageCart')
            ->willReturn($cart);
        $this->client->getContainer()->set(ShoppingCartBusinessFacade::class, $shoppingCartBusiness);

        $this->client->loginUser($testUser);

        $this->client->request('GET', '/shopping/cart/add?id=44');

        self::assertResponseRedirects('/shopping/cart');
    }

    protected function tearDown(): void
    {
        $entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        parent::tearDown();
    }
}