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
     * These fixtures are attached to UserId 1, aka. John@doe.com
     * Dynamic association doesn't seem to be necessary here.
     */
    public function load(ObjectManager $manager): void
    {
        $shoppingCart1 = new ShoppingCart();
        $shoppingCart1->setUserId(1);
        $shoppingCart1->setThumbnail('thumbnail1.com');
        $shoppingCart1->setName('firstItem');
        $shoppingCart1->setPrice(99.33);
        $shoppingCart1->setItemId(334);
        $shoppingCart1->setQuantity(3);

        $shoppingCart2 = new ShoppingCart();
        $shoppingCart2->setUserId(1);
        $shoppingCart2->setThumbnail('thumbnail2.com');
        $shoppingCart2->setName('secondItem');
        $shoppingCart2->setPrice(33.33);
        $shoppingCart2->setItemId(176268);
        $shoppingCart2->setQuantity(5);

        $shoppingCart3 = new ShoppingCart();
        $shoppingCart3->setUserId(1);
        $shoppingCart3->setThumbnail('thumbnail3.com');
        $shoppingCart3->setName('THIRDiTEM');
        $shoppingCart3->setPrice(24.99);
        $shoppingCart3->setItemId(151077);
        $shoppingCart3->setQuantity(5);

        $manager->persist($shoppingCart1);
        $manager->persist($shoppingCart2);
        $manager->persist($shoppingCart3);

        $manager->flush();
    }
}
