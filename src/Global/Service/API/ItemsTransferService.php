<?php

namespace App\Global\Service\API;

use App\Entity\Items;
use App\Global\DTO\ResponseDTO;
use App\Global\Service\Items\ItemEntityToDTOMapper;
use App\Global\Service\Items\ItemRepository;
use App\Global\Service\Mapping\Mapper;
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
        HttpClientInterface $client, Mapper $mapper, private ItemEntityToDTOMapper $itemEntityToDTOMapper
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

    public function itemTransfer(string $clubName): ResponseDTO
    {
        $item = $this->itemRepository->findOneByExternalId($clubName);
        if (!$item) {
            $itemData = $this->fetchFromFootballApi($clubName, 'teams');
            return new ResponseDTO($this->createAndPersistHomepageItems($itemData), true);
        }
        return new ResponseDTO($this->itemEntityToDTOMapper->mapItemsToDTO($item), true);}

    private function createAndPersistHomepageItems($itemData): array
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
