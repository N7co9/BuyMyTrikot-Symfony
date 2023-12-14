<?php

namespace App\Tests\Components\ShoppingCart\Communication;
namespace App\Tests\Components\ShoppingCart\Communication;

use App\Components\ShoppingCart\Communication\ShoppingCartController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartControllerTest extends WebTestCase
{
    public function testIndexAction(): void
    {
        // Create a Symfony test client
        $client = static::createClient();

        // Log in or set up a user session as needed
        $session = $client->getContainer()->get('session');
        $session->set('user', 'testuser@example.com'); // Set a user session

        // Send a GET request to the shopping cart route
        $client->request('GET', '/shopping/cart');

        // Assert that the response is successful (HTTP 200)
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // You can also assert specific content in the response if needed
        $this->assertStringContainsString('ShoppingCartController', $client->getResponse()->getContent());
    }

    public function testManageAction(): void
    {
        // Create a Symfony test client
        $client = static::createClient();

        // Log in or set up a user session as needed
        $session = $client->getContainer()->get('session');
        $session->set('user', 'testuser@example.com'); // Set a user session

        // Send a POST request to the manage route with the $slug parameter
        $client->request('POST', '/shopping/cart/test-slug');

        // Assert that the response redirects to the shopping cart route
        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
        $this->assertEquals('/shopping/cart', $client->getResponse()->headers->get('Location'));
    }
}
