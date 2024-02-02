<?php

namespace App\Global\Persistence\API;

use App\Entity\Items;
use App\Global\Persistence\Mapping\Mapper;
use App\Global\Persistence\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ItemsTransferService
{
    private $itemRepository;
    private $entityManager;
    private $client;
    private $mapper;

    public function __construct(
        ItemRepository      $itemRepository, EntityManagerInterface $entityManager,
        HttpClientInterface $client, Mapper $mapper,
    )
    {
        $this->itemRepository = $itemRepository;
        $this->entityManager = $entityManager;
        $this->client = $client;
        $this->mapper = $mapper;
    }

    private function fetchFromFootballApi($itemId, $url)
    {
        $response = $this->client->request('GET', 'http://api.football-data.org/v4/' . $url . '/' . $itemId);
        return $response->toArray();
    }

    private function fetchFromPexelApi()
    {
        $response = $this->client->request('GET', 'https://api.pexels.com/v1/search/?page=1&per_page=100&query=football+tshirt');
        return $response->toArray();
    }

    public function itemTransfer(string $clubName): array
    {
        $item = $this->itemRepository->findOneByExternalId($clubName);

        if (!$item) {
            // Fetch from external API
            $itemData = $this->fetchFromFootballApi($clubName, 'teams');

            // Create and persist new item
            return $this->createAndPersistHomepageItems($itemData);
        }
        return $this->itemRepository->findOneByExternalId($clubName);
    }

    private function createAndPersistHomepageItems($itemData)
    {
        $images = $this->fetchFromPexelApi();
        $items = $this->mapper->mapItems($images, $itemData);

        foreach ($items as $itemDTO) {
            $item = new Items();
            $item->setClubWebsite($itemDTO->clubWebsite)
                ->setClubName($itemDTO->clubName)
                ->setClubEmblem($itemDTO->clubEmblem)
                ->setClubFounded($itemDTO->clubFounded)
                ->setClubAddress($itemDTO->clubAddress)
                ->setPrice($itemDTO->price)
                ->setName($itemDTO->name)
                ->setNationality($itemDTO->nationality)
                ->setPosition($itemDTO->position)
                ->setThumbnail($itemDTO->thumbnail)
                ->setTeamId($itemDTO->team_id)
                ->setItemId($itemDTO->itemId);

            $this->entityManager->persist($item);
        }
        $this->entityManager->flush();
        return $items;
    }
}
