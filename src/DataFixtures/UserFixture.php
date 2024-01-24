<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('John@doe.com');
        $user->setUsername('John Doe');
        $user->setPassword('Qwertz123');
        $user->setRoles([]);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('Hannah@montana.com');
        $user->setUsername('Hannah Montana');
        $user->setPassword(password_hash('Qwertz123**', PASSWORD_DEFAULT));
        $user->setRoles([]);
        $manager->persist($user);

        $manager->flush();
    }
}
