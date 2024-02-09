<?php

namespace App\Tests\Components\Registration\Business;

use App\Components\Registration\Business\Model\UserRegistrationValidation;
use App\Components\Registration\Business\RegistrationBusinessFacade;
use App\Components\Registration\Persistence\UserEntityManager;
use App\Global\DTO\ResponseDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Mapping\Mapper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationBusinessFacadeTest extends WebTestCase
{
    private RegistrationBusinessFacade $registrationBusinessFacade;
    private UserRegistrationValidation $registrationValidation;
    private Mapper $mapper;
    private UserEntityManager $entityManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->mapper = $this->createMock(Mapper::class);
        $this->entityManager = self::getContainer()->get(UserEntityManager::class);
        $this->registrationValidation = $this->createMock(UserRegistrationValidation::class);
        $this->registrationBusinessFacade = new RegistrationBusinessFacade($this->registrationValidation, $this->mapper, $this->entityManager);
        parent::setUp();
    }

    public function testValidateUserWithValidData(): void
    {
        // Create a UserDTO with valid data for testing
        $userDTO = new UserDTO();
        $userDTO->username = 'John Doe';
        $userDTO->email = 'john@example.com';
        $userDTO->password = 'Password123!';

        // Mock the validation method to return an empty array (no errors)
        $this->registrationValidation->expects(self::once())->method('validate')->willReturn([]);

        // Call the validate method of the RegistrationBusinessFacade
        $errors = $this->registrationBusinessFacade->validate($userDTO);

        // Assert that there are no errors (empty array)
        self::assertEmpty($errors);
    }

    public function testValidateUserWithInvalidData(): void
    {
        // Create a UserDTO with invalid data for testing
        $userDTO = new UserDTO();
        $userDTO->username = '12345'; // Invalid username
        $userDTO->email = 'invalid-email'; // Invalid email
        $userDTO->password = 'password'; // Invalid password

        // Mock the validation method to return error responses
        $this->registrationValidation->expects(self::once())->method('validate')->willReturn([
            new ResponseDTO('Invalid username', 'Exception'),
            new ResponseDTO('Invalid email', 'Exception'),
            new ResponseDTO('Invalid password', 'Exception'),
        ]);

        // Call the validate method of the RegistrationBusinessFacade
        $errors = $this->registrationBusinessFacade->validate($userDTO);

        // Assert that there are error responses returned
        self::assertCount(3, $errors);
    }

    public function testRegisterEmailNotUnique(): void
    {
        $userDTO = new UserDTO();
        $userDTO->username = 'Valid';
        $userDTO->email = 'test@test.com';
        $userDTO->password = 'Xyz12345*';
        $this->registrationBusinessFacade->register($userDTO);


        $res = $this->registrationBusinessFacade->register($userDTO);

        self::assertSame('Email is not unique!!!', $res[0]->getMessage());
    }

    protected function tearDown(): void
    {

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        parent::tearDown();
    }
}
