<?php

namespace App\Tests\Components\ShoppingCart\Communication;
namespace App\Tests\Components\ShoppingCart\Communication;


use App\Components\ShoppingCart\Communication\ShoppingCartController;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\User;
use App\Global\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShoppingCartControllerTest extends WebTestCase
{
    public ShoppingCartController $cartController;
    public SessionInterface $session;
    public UserRepository $userRepository;
    public ShoppingCartRepository $cartRepository;

    public function setUp() : void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->cartRepository = $this->createMock(ShoppingCartRepository::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->cartController = $this->createMock(ShoppingCartController::class);
    }
    public function testIndexNoUser(): void
    {
        // Create a Symfony test client
        $client = static::createClient();

        // Send a GET request to the shopping cart route
        $client->request('GET', '/shopping/cart');

        self::assertStringContainsString('(500 Internal Server Error)', $client->getResponse()->getContent());
    }

    public function testIndexUserLoggedInNoItemsInCart(): void
    {
        $client = static::createClient();

        $user = new User();
        $user->setPassword('Xyz12345*');
        $user->setEmail('test@lol.com');

        $securityMock = $this->createMock(Security::class);
        $securityMock->method('getUser')->willReturn($user);

        $client->getContainer()->set('security.helper', $securityMock);

        $client->request('GET', '/shopping/cart');


        self::assertStringNotContainsString('(500 Internal Server Error)', $client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
    }
}
