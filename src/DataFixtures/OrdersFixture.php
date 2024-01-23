<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Orders;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrdersFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $order1 = new Orders();
        $order1->setEmail('John@doe.com');
        $order1->setFirstName('John');
        $order1->setLastName('Doe');
        $order1->setAddress('123 Main St');
        $order1->setCity('Springfield');
        $order1->setState('IL');
        $order1->setZip('62704');
        $order1->setPhoneNumber('123-456-7890');
        $order1->setDeliveryMethod('Standard');
        $order1->setPaymentMethod('Credit Card');
        $order1->setDue(100.50);
        $order1->setItems(['item1', 'item2']);
        $manager->persist($order1);

        $order2 = new Orders();
        $order2->setEmail('Hannah@montana.com');
        $order2->setFirstName('Jane');
        $order2->setLastName('Smith');
        $order2->setAddress('456 Elm St');
        $order2->setCity('Metropolis');
        $order2->setState('NY');
        $order2->setZip('10001');
        $order2->setPhoneNumber('987-654-3210');
        $order2->setDeliveryMethod('Express');
        $order2->setPaymentMethod('PayPal');
        $order2->setDue(250.75);
        $order2->setItems(['item3', 'item4']);
        $manager->persist($order2);

        $manager->flush();
    }
}
