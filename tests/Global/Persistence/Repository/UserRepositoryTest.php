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
        $user = new User();
        $user->setPassword('old_password');

        $newHashedPassword = 'new_hashed_password';

        $this->userRepository->upgradePassword($user, $newHashedPassword);

        $this->assertEquals($newHashedPassword, $user->getPassword());
    }

    public function testFindOneByEmail(): void
    {
        $email = 'test@lol.com';

        $user = $this->userRepository->findOneByEmail($email);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testUpgradePasswordWithUnsupportedUserException(): void
    {
        $unsupportedUser = $this->createMock(PasswordAuthenticatedUserInterface::class);

        $this->expectException(UnsupportedUserException::class);

        $this->userRepository->upgradePassword($unsupportedUser, 'new_hashed_password');
    }

    // ... Other test methods ...
}
