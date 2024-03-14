<?php

namespace App\Components\Homepage\Business;

use App\Global\DTO\ResponseDTO;
use App\Global\Service\API\ItemsTransferService;

class HomepageBusinessFacade implements HomepageBusinessFacadeInterface
{
    public function __construct
    (private readonly ItemsTransferService $itemsTransferService)
    {
    }

    public function itemTransfer(string $slug): ?ResponseDTO
    {
        return $this->itemsTransferService->itemTransfer($slug);
    }
}