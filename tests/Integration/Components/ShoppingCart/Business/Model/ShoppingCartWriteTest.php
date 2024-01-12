<?php

namespace App\Tests\Integration\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Business\Model\ShoppingCartWrite;
use App\Components\ShoppingCart\Business\ShoppingCartBusinessFacade;
use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\ShoppingCart;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingCartWriteTest extends KernelTestCase
{
    private ShoppingCartBusinessFacade $shoppingCartBusinessFacade;

    /**
     * @var ShoppingCartRepository
     */
    private ShoppingCartRepository $shoppingCartRepository;

    private ShoppingCartEntityManager $shoppingCartEntityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $container = self::getContainer();

        $this->entityManager = $container->get('doctrine')->getManager();
        /** @var ShoppingCartBusinessFacade $shoppingCartBusinessFacade */
        $shoppingCartBusinessFacade = $container->get(ShoppingCartBusinessFacade::class);
        $this->shoppingCartBusinessFacade = $shoppingCartBusinessFacade;

        /** @var ShoppingCartRepository $shoppingCartRepository */
        $shoppingCartRepository = $container->get(ShoppingCartRepository::class);
        $this->shoppingCartRepository = $shoppingCartRepository;

        /** @var ShoppingCartEntityManager $shoppingCartEntityManager */
        $shoppingCartEntityManager = $container->get(ShoppingCartEntityManager::class);
        $this->shoppingCartEntityManager = $shoppingCartEntityManager;

    }

    protected function tearDown(): void
    {
        $this->entityManager->createQuery('DELETE FROM ' . ShoppingCart::class)->execute();
        $this->entityManager->close();

        parent::tearDown();
    }

    public function testAddItemToCart(): void
    {
        $expectedShoppingCartDto = new ShoppingCartSaveDTO(
            quantity: 2,itemId: 99, userId: 1
        );
        $this->shoppingCartBusinessFacade->saveItemToCart($expectedShoppingCartDto);

        $shoppingCartDto = $this->shoppingCartRepository->findOneByUserIdAndItemId($expectedShoppingCartDto->userId, $expectedShoppingCartDto->itemId);

        self::assertSame($expectedShoppingCartDto->itemId, $shoppingCartDto->itemId);
        self::assertSame($expectedShoppingCartDto->quantity, $shoppingCartDto->quantity);
        self::assertSame($expectedShoppingCartDto->userId, $shoppingCartDto->userId);
        self::assertGreaterThan(0, $shoppingCartDto->id);
    }


    public function testUpdateItemToCart(): void
    {
        $this->shoppingCartEntityManager->save(new ShoppingCartSaveDTO(
            quantity: 42,itemId: 99, userId: 1
        ));

        $expectedShoppingCartDto = new ShoppingCartSaveDTO(
            quantity: 11,itemId: 99, userId: 1
        );

        $this->shoppingCartBusinessFacade->saveItemToCart($expectedShoppingCartDto);

        $shoppingCartDto = $this->shoppingCartRepository->findOneByUserIdAndItemId($expectedShoppingCartDto->userId, $expectedShoppingCartDto->itemId);

        self::assertSame($expectedShoppingCartDto->itemId, $shoppingCartDto->itemId);
        self::assertSame($expectedShoppingCartDto->quantity, $shoppingCartDto->quantity);
        self::assertSame($expectedShoppingCartDto->userId, $shoppingCartDto->userId);
        self::assertGreaterThan(0, $shoppingCartDto->id);
    }

}