<?php
declare(strict_types=1);

namespace App\Tests\Unit\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Business\Model\ShoppingCartRead;
use App\Components\ShoppingCart\Dto\ShoppingCartSaveDTO;
use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Items;
use App\Global\Persistence\Repository\ItemRepository;
use PHPUnit\Framework\TestCase;

class ShoppingCartReadTest extends TestCase
{

    public function testGetCart(): void
    {
        $cartForMockingList = [
            new ShoppingCartSaveDTO(quantity: 1, itemId: 334, userId: 55, id: 3),
        ];

        $shoppingCartRepositoryStub = $this->createStub(ShoppingCartRepository::class);
        $shoppingCartRepositoryStub->expects(self::once())
            ->method('findByUserId')
            ->with(55)
            ->willReturn($cartForMockingList);

        $itemStub = new Items();
        $itemStub->setName('lol');
        $itemStub->setPrice(303.03);
        $itemStub->setThumbnail('lol.com');
        $itemStub->setThumbnail('wooww.com');
        $itemStub->setItemId(334);

        $itemRepositoryStub = $this->createStub(ItemRepository::class);
        $itemRepositoryStub->method('findOneByItemId')
            ->willReturn($itemStub);

        $shoppingCardRead = new ShoppingCartRead($shoppingCartRepositoryStub, $itemRepositoryStub);
        $res = $shoppingCardRead->getCart(55);

        self::assertSame(1, $res[0]->quantity);
        self::assertSame(303.03, $res[0]->price);
    }
}