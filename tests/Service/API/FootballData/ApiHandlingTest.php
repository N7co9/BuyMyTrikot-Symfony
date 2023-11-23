<?php

namespace App\Tests\Service\API\FootballData;

use App\Service\API\FootballData\FootballApiHandling;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiHandlingTest extends KernelTestCase
{
    private HttpClientInterface $httpClient;
  //  private $cache;

    protected function setUp(): void
    {
        $this->httpClient = self::getContainer()->get(HttpClientInterface::class);
        parent::setUp();
    }
    public function testRequestItemInfo()
    {
        $apiHandling = new FootballApiHandling($this->httpClient);
        $response = $apiHandling->requestItemInfo(44);

        $this->assertSame(44, $response['id']);
    }
}
