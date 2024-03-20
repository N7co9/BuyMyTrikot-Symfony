<?php
declare(strict_types=1);

namespace App\Global\Service\Items;

use App\Global\DTO\ItemDTO;

class ItemEntityToDTOMapper
{
    public  function mapItemsToDTO(array $items): array
    {
        $itemDTOs = [];

        foreach ($items as $item) {
            $itemDTO = new ItemDTO();

            $itemDTO->id = (string) $item->getId();
            $itemDTO->price = (float) $item->getPrice();
            $itemDTO->name = (string) $item->getName();
            $itemDTO->nationality = (string) $item->getNationality();
            $itemDTO->position = (string) $item->getPosition();
            $itemDTO->thumbnail = (string) $item->getThumbnail();
            $itemDTO->clubName = (string) $item->getClubName();
            $itemDTO->clubAddress = (string) $item->getClubAddress();
            $itemDTO->clubFounded = (string) $item->getClubFounded();
            $itemDTO->clubWebsite = (string) $item->getClubWebsite();
            $itemDTO->clubEmblem = (string) $item->getClubEmblem();
            $itemDTO->team_id = (int) $item->getTeamId();
            $itemDTO->quantity = 0;
            $itemDTO->user_id = '';
            $itemDTO->itemId = (int) $item->getItemId();

            $itemDTOs[] = $itemDTO;
        }

        return $itemDTOs;
    }
}