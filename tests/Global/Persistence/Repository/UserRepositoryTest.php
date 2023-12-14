<?php

namespace App\Tests\Global\Persistence\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Global\Persistence\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        // Get the entity manager
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Get the UserRepository
        $this->userRepository = $this->entityManager
            ->getRepository(User::class);
    }

    public function testUpgradePassword(): void
    {
        // Create a user
        $user = new User();
        $user->setPassword('old_password'); // Set an initial password

        $newHashedPassword = 'new_hashed_password';

        $this->userRepository->upgradePassword($user, $newHashedPassword);

        // Ensure the user's password has been updated
        $this->assertEquals($newHashedPassword, $user->getPassword());
    }

    public function testFindOneByEmail(): void
    {
        // Replace with an existing email in your database
        $email = 'test@lol.com';

        $user = $this->userRepository->findOneByEmail($email);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testUpgradePasswordWithUnsupportedUserException(): void
    {
        // Create a user that doesn't extend User
        $unsupportedUser = $this->createMock(PasswordAuthenticatedUserInterface::class);

        // Ensure that an UnsupportedUserException is thrown
        $this->expectException(UnsupportedUserException::class);

        $this->userRepository->upgradePassword($unsupportedUser, 'new_hashed_password');
    }

    // ... Other test methods ...
}
