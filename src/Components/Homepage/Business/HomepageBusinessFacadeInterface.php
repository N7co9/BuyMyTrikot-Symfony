<?php

namespace App\Components\Homepage\Business;

use App\Global\DTO\ResponseDTO;

interface HomepageBusinessFacadeInterface
{
    /**
     * Retrieve items based on a slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function itemTransfer(string $slug): ?ResponseDTO;
}