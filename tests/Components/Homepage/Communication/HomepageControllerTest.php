<?php

namespace App\Tests\Components\Homepage\Communication;

use App\Entity\User;
use App\Global\Persistence\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Stopwatch\Section;

class HomepageControllerTest extends WebTestCase
{
    public Security $security;
    public function setUp() : void
    {
        $this->security = $this->createMock(Security::class);
    }
    public function testHomepageNoUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/home/');

        self::assertStringNotContainsString('Nice to have you here', $client->getResponse()->getContent());
        self::assertNotEmpty($client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
    }

    public function testHomePageUser(): void
    {
        $client = static::createClient();

        $userRepo = static::getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneByEmail('test@lol.com');
        $client->loginUser($user);

        $client->request('GET', '/home/');

        self::assertStringContainsString('Nice to have you here', $client->getResponse()->getContent());
        self::assertResponseIsSuccessful();
    }

    public function testBrowseNotValid()
    {
        $client = static::createClient();

        $client->request('GET', '/home/browse/gIbeRisH');

        self::assertStringContainsString('Sorry, we couldn’t find the page you’re looking for', $client->getResponse()->getContent());
    }

    public function testBrowseValid()
    {
        $client = static::createClient();

        $client->request('GET', '/home/browse/test');

        self::assertStringNotContainsString('Sorry, we couldn’t find the page you’re looking for', $client->getResponse()->getContent());
    }

    public function testSearchRedirect()
    {
        $client = static::createClient();
        $client->request('GET', '/home/search?query=search_query');

        self::assertResponseRedirects('/home/browse/search_query');
    }
}
