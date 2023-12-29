<?php

namespace App\Tests\Components\Authentication\Business;

use App\Components\Authentication\Business\AuthenticationBusinessFacade;
use App\Components\Authentication\Business\UserLoginValidation;
use App\Global\Persistence\DTO\ResponseDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationBusinessFacadeTest extends TestCase
{
    public function testAuthenticateSuccess()
    {
        // Create a mock for UserLoginValidation
        $loginValidation = $this->createMock(UserLoginValidation::class);

        // Create a mock for SessionInterface
        $session = $this->createMock(SessionInterface::class);

        // Set up the expected response from UserLoginValidation
        $expectedResponse = new ResponseDTO('Login successful!', 'OK');

        // Configure the mock to return the expected response
        $loginValidation->expects(self::once())
            ->method('authenticate')
            ->willReturn($expectedResponse);

        // Create an instance of AuthenticationBusinessFacade with the mocked dependencies
        $businessFacade = new AuthenticationBusinessFacade($loginValidation);

        // Call the authenticate method with sample data
        $formData = ['email' => 'test@lol.com', 'password' => 'Xyz12345*'];
        $response = $businessFacade->authenticate($formData, $session);

        // Assert that the response matches the expected response
        self::assertSame($expectedResponse, $response);
    }

    public function testAuthenticateFailure()
    {
        // Create a mock for UserLoginValidation
        $loginValidation = $this->createMock(UserLoginValidation::class);

        // Create a mock for SessionInterface
        $session = $this->createMock(SessionInterface::class);

        // Set up the expected response from UserLoginValidation
        $expectedResponse = new ResponseDTO('User not found', 'Error');

        // Configure the mock to return the expected response
        $loginValidation->expects(self::once())
            ->method('authenticate')
            ->willReturn($expectedResponse);

        // Create an instance of AuthenticationBusinessFacade with the mocked dependencies
        $businessFacade = new AuthenticationBusinessFacade($loginValidation);

        // Call the authenticate method with sample data
        $formData = ['email' => 'test@example.com', 'password' => 'password'];
        $response = $businessFacade->authenticate($formData, $session);

        // Assert that the response matches the expected response
        self::assertSame($expectedResponse, $response);
    }
}
