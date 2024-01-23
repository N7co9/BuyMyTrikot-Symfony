<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Items;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ItemsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $item1 = new Items();
        $item1->setClubWebsite('https://exampleclub1.com');
        $item1->setClubName('Example Club 1');
        $item1->setClubEmblem('emblem1.png');
        $item1->setClubFounded('1900');
        $item1->setClubAddress('123 Example Street, City');
        $item1->setPrice(99.99);
        $item1->setName('Player 1');
        $item1->setNationality('Country1');
        $item1->setPosition('Goalkeeper');
        $item1->setThumbnail('thumbnail1.png');
        $item1->setTeamId('1');
        $item1->setItemId(101);
        $manager->persist($item1);

        $item2 = new Items();
        $item2->setClubWebsite('https://exampleclub2.com');
        $item2->setClubName('Example Club 2');
        $item2->setClubEmblem('emblem2.png');
        $item2->setClubFounded('1950');
        $item2->setClubAddress('456 Another Road, City');
        $item2->setPrice(149.99);
        $item2->setName('Player 2');
        $item2->setNationality('Country2');
        $item2->setPosition('Forward');
        $item2->setThumbnail('thumbnail2.png');
        $item2->setTeamId('2');
        $item2->setItemId(102);
        $manager->persist($item2);

        $manager->flush();
    }
}
