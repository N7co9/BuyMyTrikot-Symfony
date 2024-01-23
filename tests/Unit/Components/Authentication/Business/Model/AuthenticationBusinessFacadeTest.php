<?php
declare(strict_types=1);

namespace App\Tests\Unit\Components\Authentication\Business\Model;

use App\Components\Authentication\Business\AuthenticationBusinessFacade;
use App\Components\Authentication\Business\Model\UserLoginValidation;
use App\Entity\User;
use App\Global\Persistence\Repository\UserRepository;
use Monolog\Test\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationBusinessFacadeTest extends TestCase
{
    private readonly AuthenticationBusinessFacade $authenticationBusinessFacade;
    private UserRepository $userRepository;
    private SessionInterface $session;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->userLoginValidation = new UserLoginValidation($this->userRepository);
        $this->authenticationBusinessFacade = new AuthenticationBusinessFacade($this->userLoginValidation);
    }

    public function testAuthenticateWithEmptyEmailAndPassword(): void
    {
        $formData = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->authenticationBusinessFacade->authenticate($formData, $this->session);

        $this->assertEquals('Email or password cannot be empty', $response->getMessage());
        $this->assertEquals('Error', $response->getType());
    }

    public function testAuthenticateWithUserNotFound(): void
    {
        $formData = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        $this->userRepository->method('findOneByEmail')->willReturn(null);

        $response = $this->authenticationBusinessFacade->authenticate($formData, $this->session);

        $this->assertEquals('User not found', $response->getMessage());
        $this->assertEquals('Error', $response->getType());
    }

    public function testAuthenticateWithInvalidCredentials(): void
    {
        $formData = [
            'email' => 'validation@validation.com',
            'password' => 'invalidPassword',
        ];

        $hashedPassword = password_hash('validPassword', PASSWORD_BCRYPT);
        $user = new User();
        $user->setEmail('existing@example.com');
        $user->setPassword($hashedPassword);

        $this->userRepository->method('findOneByEmail')->willReturn($user);

        $response = $this->authenticationBusinessFacade->authenticate($formData, $this->session);

        $this->assertEquals('Invalid credentials', $response->getMessage());
        $this->assertEquals('Error', $response->getType());
    }

    public function testAuthenticateWithValidCredentials(): void
    {
        $formData = [
            'email' => 'existing@example.com',
            'password' => 'validPassword',
        ];

        $hashedPassword = password_hash('validPassword', PASSWORD_BCRYPT);
        $user = new User();
        $user->setEmail('existing@example.com');
        $user->setPassword($hashedPassword);

        $this->userRepository->method('findOneByEmail')->willReturn($user);

        $this->session->expects($this->once())->method('set')->with('user', 'existing@example.com');

        $response = $this->authenticationBusinessFacade->authenticate($formData, $this->session);

        $this->assertEquals('Login successful!', $response->getMessage());
        $this->assertEquals('OK', $response->getType());
    }
}