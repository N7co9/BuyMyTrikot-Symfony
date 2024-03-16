<?php

namespace App\Tests\Global\Persistence\Repository;

use App\Components\Registration\Persistence\RegistrationEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Entity\User;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(RegistrationEntityManager::class);

        $userDTO = new UserDTO();
        $userDTO->email = 'test@lol.com';
        $this->entityManager->register($userDTO);

        $this->userRepository =  self::getContainer()->get(UserRepository::class);

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

    protected function tearDown(): void
    {
        parent::tearDown();

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
    }
}
