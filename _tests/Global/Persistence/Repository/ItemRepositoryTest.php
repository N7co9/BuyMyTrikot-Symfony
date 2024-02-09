<?php

namespace App\Tests\Global\Persistence\Repository;

use App\Entity\Items;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ItemRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $itemRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        // Get the entity manager
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Get the ItemRepository
        $this->itemRepository = $this->entityManager
            ->getRepository(Items::class);
    }

    public function testFindOneByItemId(): void
    {
        // Replace with an existing item_id in your database
        $itemId = 1337;

        $item = $this->itemRepository->findOneByItemId($itemId);

        $this->assertInstanceOf(Items::class, $item);
    }

    public function testFindManyByTeamId(): void
    {
        // Replace with an existing team_id in your database
        $teamId = 5;

        $items = $this->itemRepository->findManyByTeamId($teamId);

        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertInstanceOf(Items::class, $items[0]);
    }

    public function testFindItemsByArrayOfIds(): void
    {
        // Replace with an array of existing item IDs in your database
        $itemIds = [['id' => 1337], ['id' => 420]];

        $items = $this->itemRepository->findItemsByArrayOfIds($itemIds);

        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertInstanceOf(Items::class, $items[0]);
    }

    public function testFindOneByExternalId(): void
    {
        // Replace with an existing externalId in your database
        $externalId = 'test';

        $items = $this->itemRepository->findOneByExternalId($externalId);

        $this->assertIsArray($items);
        $this->assertNotEmpty($items);
        $this->assertInstanceOf(Items::class, $items[0]);
    }
}
