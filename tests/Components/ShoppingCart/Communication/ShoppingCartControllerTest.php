<?php

namespace App\Tests\Components\ShoppingCart\Communication;
namespace App\Tests\Components\ShoppingCart\Communication;


use App\Components\Orderflow\Persistence\OrderFlowEntityManager;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacade;
use App\Components\ShoppingCart\Communication\ShoppingCartController;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\ShoppingCart;
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

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@lol.com');

        $client->loginUser($testUser);

        $client->request('GET', '/shopping/cart');

        self::assertStringNotContainsString('(500 Internal Server Error)', $client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
    }

    public function testManage() : void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('test@lol.com');

        $cart = new ShoppingCart();

        $shoppingCartBusiness = $this->createMock(ShoppingCartBusinessFacade::class);
        $shoppingCartBusiness->method('manageCart')
            ->willReturn($cart);
        $client->getContainer()->set(ShoppingCartBusinessFacade::class, $shoppingCartBusiness);

        $client->loginUser($testUser);

        $client->request('GET', '/shopping/cart/add?id=44');

        self::assertResponseRedirects('/shopping/cart');
    }
}
