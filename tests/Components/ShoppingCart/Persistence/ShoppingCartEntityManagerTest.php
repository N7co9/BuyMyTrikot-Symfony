<?php

namespace App\Tests\Components\ShoppingCart\Persistence;

use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\ShoppingCart;
use App\Global\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class ShoppingCartEntityManagerTest extends WebTestCase
{
    private $entityManager;
    private $cartRepository;
    private $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->cartRepository = self::getContainer()->get(ShoppingCartRepository::class);

        parent::setUp();
    }

    public function testPersistNew()
    {
        $userRepo = static::getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $cart = new ShoppingCart();
        $cart->setId(100);
        $cart->setItemId('69');
        $cart->setQuantity('69');
        $cart->setUserId(1);
        $cart->setPrice(69.420);
        $cart->setName('name');
        $cart->setThumbnail('thumbnail');

        $manager = new ShoppingCartEntityManager($this->userRepository, $this->entityManager, $this->cartRepository);
        $manager->persist($cart);



        $resultsUWU = $this->cartRepository->findByUserId(1);
        self::assertSame('thumbnail', $resultsUWU[0]->getThumbnail());
        self::assertSame(69, $resultsUWU[0]->getItemId());

    }
    public function testPersistRemove()
    {
        $userRepo = static::getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $cart = new ShoppingCart();
        $cart->setId(100);
        $cart->setItemId('69');
        $cart->setQuantity(1);
        $cart->setUserId(1);
        $cart->setPrice(69.420);
        $cart->setName('name');
        $cart->setThumbnail('thumbnail');

        $manager = new ShoppingCartEntityManager($this->userRepository, $this->entityManager, $this->cartRepository);
        $manager->persist($cart);

        $cart->setQuantity(0);
        $manager->persist($cart);

        $resultsUWU = $this->cartRepository->findByUserId(1);
        self::assertEmpty($resultsUWU);
    }

    public function testRemoeAllItemsFromUser()
    {
        $userRepo = static::getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $cart = new ShoppingCart();
        $cart->setId(100);
        $cart->setItemId('69');
        $cart->setQuantity(1);
        $cart->setUserId(1);
        $cart->setPrice(69.420);
        $cart->setName('name');
        $cart->setThumbnail('thumbnail');

        $manager = new ShoppingCartEntityManager($this->userRepository, $this->entityManager, $this->cartRepository);
        $manager->persist($cart);

        $cart = new ShoppingCart();
        $cart->setId(111);
        $cart->setItemId('111');
        $cart->setQuantity(1);
        $cart->setUserId(1);
        $cart->setPrice(69.420);
        $cart->setName('name');
        $cart->setThumbnail('thumbnail');

        $manager->persist($cart);

        $manager->removeAllItemsFromUser('test@lol.com',$this->entityManager, $this->cartRepository, $this->userRepository);

        $resultsUWU = $this->cartRepository->findByUserId(1);
        self::assertEmpty($resultsUWU);
    }
    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM shopping_cart');
        $connection->executeQuery('ALTER TABLE shopping_cart AUTO_INCREMENT=0');

        $connection->close();
    }
}
