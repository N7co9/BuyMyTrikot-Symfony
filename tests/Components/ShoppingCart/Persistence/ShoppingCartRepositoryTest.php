<?php

namespace App\Tests\Components\ShoppingCart\Persistence;

use App\Entity\ShoppingCart;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingCartRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $cartRepository;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        // Get the entity manager
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Get the UserRepository
        $this->cartRepository = $this->entityManager
            ->getRepository(ShoppingCart::Class);
    }

    public function testFindOneByUserIdAndItemId()
    {
        $res = $this->cartRepository->findOneByUserIdAndItemId(1, 999);

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
        self::assertSame(109.67, $res['total']);
    }

    public function testFindCartItemsByEmail()
    {
        $res = $this->cartRepository->findCartItemsByEmail('test@lol.com');

        self::assertSame(999, $res[0]['id']);
    }
}
