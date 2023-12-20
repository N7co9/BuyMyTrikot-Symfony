<?php

namespace App\Tests\Components\Authentication\Communication;

use App\Components\Authentication\Communication\AuthenticationController;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();

        // Simulate accessing the login page
        $client->request('GET', '/login');

        self::assertStringContainsString('Login', $client->getResponse()->getContent());
    }
}
