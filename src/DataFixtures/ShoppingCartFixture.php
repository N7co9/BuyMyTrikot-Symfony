<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ShoppingCart;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ShoppingCartFixture extends Fixture
{
    /*
     * These fixtures are attached to UserId 3, aka. John@doe.com
     * Dynamic association doesn't seem to be necessary here.
     */
    public function load(ObjectManager $manager): void
    {
        $item1 = 1;
        $item2 = 2;

        $shoppingCart1 = new ShoppingCart();
        $shoppingCart1->setUserId(3);
        $shoppingCart1->setThumbnail('thumbnail1.com');
        $shoppingCart1->setName('firstItem');
        $shoppingCart1->setPrice(99.33);
        $shoppingCart1->setItemId($item1);
        $shoppingCart1->setQuantity(3);
        $manager->persist($shoppingCart1);

        $shoppingCart2 = new ShoppingCart();
        $shoppingCart2->setUserId(3);
        $shoppingCart1->setThumbnail('thumbnail2.com');
        $shoppingCart1->setName('secondItem');
        $shoppingCart1->setPrice(33.33);
        $shoppingCart2->setItemId($item2);
        $shoppingCart2->setQuantity(5);
        $manager->persist($shoppingCart2);

        $manager->flush();
    }
}
