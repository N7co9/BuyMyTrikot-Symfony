<?php

namespace App\Components\ShoppingCart\Business\Model;

use App\Components\ShoppingCart\Persistence\Dto\ShoppingCartSaveDTO;
use Symfony\Component\HttpFoundation\Request;

interface CartWriteInterface
{
    public function save(Request $request): void;

    public function remove(Request $request): void;

    public function buildShoppingCartSaveDTO(Request $request): ShoppingCartSaveDTO;

    public function removeAllAfterOrderSuccess(Request $request) : void;
}