<?php

namespace App\Service\API\FootballData;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class FootballApiHandling {
    public function __construct
    (
        private HttpClientInterface $httpClient,
        private CacheInterface $cache
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    private function makeApiRequest(string $url): array
    {
        $cacheKey = 'api_' . md5($url);
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($url) {
            $item->expiresAfter(3600); // Set cache expiration, e.g., 1 hour

            // Make the API request
            $response = $this->httpClient->request('GET', 'http://api.football-data.org/v4/' . $url);

            // Return the response as an array
            return $response->toArray();
        });
    }

    public function requestPlayerDataFromTeam($teamID): array
    {
        return $this->makeApiRequest('teams/' . $teamID);
    }

    public function requestItemInfo($itemID): array
    {
        return $this->makeApiRequest('persons/' . $itemID);
    }
}