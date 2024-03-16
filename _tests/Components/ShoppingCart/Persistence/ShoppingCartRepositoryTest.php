<?php

namespace App\Tests\Components\ShoppingCart\Persistence;

use App\Components\Registration\Persistence\RegistrationEntityManager;
use App\Entity\ShoppingCart;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingCartRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $cartRepository;

    public function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(RegistrationEntityManager::class);
        $userDTO = new UserDTO();
        $userDTO->email = 'test@lol.com';
        $userDTO->username = 'test';
        $userDTO->password = 'Xyz12345*';
        $this->entityManager->register($userDTO);

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->cartRepository = $this->entityManager
            ->getRepository(ShoppingCart::Class);

        $connection = $this->entityManager->getConnection();
        $connection->insert('shopping_cart', [
            'id' => 1,
            'item_id' => 1,
            'quantity' => 1,
            'user_id' => 1,
            'price' => 1,
            'name' => 'eins',
            'thumbnail' => 'zwei',
        ]);
    }

    public function testFindOneByUserIdAndItemId()
    {
        $res = $this->cartRepository->findOneByUserIdAndItemId(1, 1);

        self::assertNotEmpty($res);
        self::assertSame(1, $res->getId());
    }

    public function testFindByUserId()
    {
        $res = $this->cartRepository->findByUserId(1);

        self::assertNotEmpty($res);
        self::assertIsArray($res);
    }

    public function testGetTotal()
    {
        $res = $this->cartRepository->getTotal(1);

        self::assertNotEmpty($res);
        self::assertSame(6.140000000000001, $res['total']);
    }

    public function testFindCartItemsByEmail()
    {
        $res = $this->cartRepository->findCartItemsByEmail('test@lol.com');

        self::assertSame(1, $res[0]['id']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM shopping_cart');
        $connection->executeQuery('ALTER TABLE shopping_cart AUTO_INCREMENT=0');

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
    }
}
