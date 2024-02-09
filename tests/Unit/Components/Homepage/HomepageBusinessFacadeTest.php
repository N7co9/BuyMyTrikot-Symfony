<?php
declare(strict_types=1);

namespace App\Tests\Unit\Components\Homepage;

use App\Components\Homepage\Business\HomepageBusinessFacade;
use App\Global\Service\API\ItemsTransferService;
use App\Global\Service\Items\ItemRepository;
use App\Global\Service\Mapping\Mapper;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Test\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomepageBusinessFacadeTest extends TestCase
{
    private readonly HomepageBusinessFacade $homepageBusinessFacade;
    private readonly ItemsTransferService $itemsTransferService;
    private readonly ItemRepository $itemRepository;
    private readonly EntityManagerInterface $entityManager;
    private readonly HttpClientInterface $client;
    private readonly Mapper $mapper;

    public function testItemTransferInCache(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->mapper = $this->createMock(Mapper::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $item =
            [
                [
                    'id' => 641,
                    'club_website' => 'http://www.bvb.de',
                    'club_name' => 'Borussia Dortmund',
                    'club_emblem' => 'https://crests.football-data.org/4.png',
                    'club_founded' => 1909,
                    'club_address' => 'Rheinlanddamm 207-209 Dortmund 44137',
                    'price' => 20.95,
                    'name' => 'Gregor Kobel',
                    'nationality' => 'Switzerland',
                    'position' => 'Goalkeeper',
                    'thumbnail' => 'https://images.pexels.com/photos/15033212/pexels-photo-15033212.jpeg',
                    'team_id' => 4,
                    'item_id' => 334
                ],
                [
                    'id' => 642,
                    'club_website' => 'http://www.bvb.de',
                    'club_name' => 'Borussia Dortmund',
                    'club_emblem' => 'https://crests.football-data.org/4.png',
                    'club_founded' => 1909,
                    'club_address' => 'Rheinlanddamm 207-209 Dortmund 44137',
                    'price' => 25.95,
                    'name' => 'Alexander Meyer',
                    'nationality' => 'Germany',
                    'position' => 'Goalkeeper',
                    'thumbnail' => 'https://images.pexels.com/photos/4933846/pexels-photo-4933846.jpeg',
                    'team_id' => 4,
                    'item_id' => 9389
                ]
            ];

        $this->itemRepository = $this->createMock(ItemRepository::class);
        $this->itemRepository->method('findOneByExternalId')
            ->willReturn($item);

        $this->itemsTransferService = new ItemsTransferService($this->itemRepository, $this->entityManager, $this->client, $this->mapper);

        $this->homepageBusinessFacade = new HomepageBusinessFacade($this->itemsTransferService);

        $res = $this->homepageBusinessFacade->itemTransfer('Borussia Dortmund');

        self::assertSame(334, $res[0]['item_id']);
        self::assertSame(9389, $res[1]['item_id']);


        self::assertSame('Switzerland', $res[0]['nationality']);
        self::assertSame('Germany', $res[1]['nationality']);


        self::assertSame('https://images.pexels.com/photos/15033212/pexels-photo-15033212.jpeg', $res[0]['thumbnail']);
        self::assertSame('https://images.pexels.com/photos/4933846/pexels-photo-4933846.jpeg', $res[1]['thumbnail']);
    }
}