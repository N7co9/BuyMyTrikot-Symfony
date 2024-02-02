<?php

namespace App\Components\Homepage\Business;

use App\Global\Persistence\API\ItemsTransferService;

class HomepageBusinessFacade implements HomepageBusinessFacadeInterface
{
    public function __construct
    (private readonly ItemsTransferService $itemsTransferService)
    {
    }

    public function itemTransfer(string $slug): ?array
    {
        return $this->itemsTransferService->itemTransfer($slug);
    }
}