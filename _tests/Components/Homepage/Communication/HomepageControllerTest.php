<?php

namespace App\Tests\Components\Homepage\Communication;

use App\Components\Registration\Persistence\RegistrationEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;

class HomepageControllerTest extends WebTestCase
{
    public Security $security;
    public function setUp() : void
    {
        $this->client = self::createClient();
        $this->entityManager = $this->client->getContainer()->get(RegistrationEntityManager::class);
        $userDTO = new UserDTO();
        $userDTO->email = 'test@lol.com';
        $userDTO->username = 'test';
        $userDTO->password = 'Xyz12345*';
        $this->entityManager->register($userDTO);

        $this->security = $this->createMock(Security::class);
        parent::setUp();
    }
    public function testHomepageNoUser(): void
    {
        $this->client->request('GET', '/home/');

        self::assertStringNotContainsString('Nice to have you here', $this->client->getResponse()->getContent());
        self::assertNotEmpty($this->client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
    }

    public function testHomePageUser(): void
    {
        $userRepo = static::getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $this->client->loginUser($user);

        $this->client->request('GET', '/home/');

        self::assertStringContainsString('Nice to have you here', $this->client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
    }

    public function testBrowseNotValid()
    {
        $this->client->request('GET', '/home/browse/gIbeRisH');

        self::assertStringContainsString('Sorry, we couldn’t find the page you’re looking for', $this->client->getResponse()->getContent());
    }

    public function testBrowseValid()
    {
        $this->client->request('GET', '/home/browse/test');

        self::assertStringNotContainsString('Sorry, we couldn’t find the page you’re looking for', $this->client->getResponse()->getContent());
    }

    public function testSearchRedirect()
    {
        $this->client->request('GET', '/home/search?query=search_query');

        self::assertResponseRedirects('/home/browse/search_query');
    }

    protected function tearDown(): void
    {
        $entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        parent::tearDown();
    }
}
