<?php

namespace App\Repositories\SaleItem;

use App\Models\SaleItem;

interface SaleItemRepositoryInterface
{
    /**
     * Insert new sale item into database
     */
    public function add(int $idSale,array $data): ?SaleItem;

    /**
     * List all items of sale by id into database
     */
    public function getAllByIdSale(int $idSale): array;
}
