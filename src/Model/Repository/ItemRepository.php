<?php

namespace App\Model\Repository;

use App\Service\API\FootballData\FootballApiHandling;
use App\Service\API\Pexel\PexelApiHandling;
use App\Service\Mapping\Mapper;
class ItemRepository
{
    public function __construct(
        private FootballApiHandling $footballApiHandling,
        private PexelApiHandling $pexelApiHandling,
        private Mapper $mapper
    )
    {

    }
    public function getItems(string $teamID) : array
        // returns a list of ItemDTOs from team/teamID.
    {
        $fbArray = $this->footballApiHandling->requestPlayerDataFromTeam($teamID);
        $imageArray = $this->pexelApiHandling->makeApiRequest();

        return $this->mapper->mapItems($imageArray, $fbArray);
    }
}