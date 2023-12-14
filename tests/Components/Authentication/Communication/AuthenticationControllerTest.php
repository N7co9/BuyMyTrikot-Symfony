<?php

namespace App\Tests\Components\Authentication\Communication;

use App\Components\Authentication\Communication\AuthenticationController;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        // Create a Symfony test client
        $client = static::createClient();

        // Send a POST request to the login route with valid data and handle redirection
        $crawler = $client->request('POST', '/login');

        $form = $crawler->selectButton('Login')->form();
        $form['login_form[email]'] = 'test@lol.com';
        $form['login_form[password]'] = 'Xyz12345*';

        // Submit the form and follow redirection
        $client->submit($form);

        self::assertSame(302, $client->getResponse()->getStatusCode()); // Assert that the status code is 302 (Found/Redirect)
        self::assertSame('/home/', $client->getResponse()->headers->get('location')); // Assert the redirection location

    }
    public function testLogout(): void
    {
        // Create a Symfony test client
        $client = static::createClient();

        // Send a GET request to the logout route
        $client->request('GET', '/logout');

        $content = $client->getResponse()->getContent();

        // Assert that the response is a redirection (status code 302)
        self::assertSame(302, $client->getResponse()->getStatusCode());

        // Assert that the redirection is to the homepage route
        self::assertSame('http://localhost/home/', $client->getResponse()->headers->get('location'));
    }
}
