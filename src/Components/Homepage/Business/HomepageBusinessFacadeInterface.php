<?php

namespace App\Components\Homepage\Business;

interface HomepageBusinessFacadeInterface
{
    /**
     * Retrieve items based on a slug.
     *
     * @param string $slug
     * @return array|null
     */
    public function itemTransfer(string $slug): ?array;
}