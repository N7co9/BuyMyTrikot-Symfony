<?php

namespace App\Service\API\Pexel;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class PexelApiHandling {
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function makeApiRequest(): array
    {
        $cacheKey = 'pexel_api_search_football_tshirt';

        return $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(3600); // Cache for 1 hour, for example

            // Make the actual API request
            return $this->httpClient->request('GET', 'https://api.pexels.com/v1/search/?page=1&per_page=100&query=football+tshirt')->toArray();
        });
    }
}