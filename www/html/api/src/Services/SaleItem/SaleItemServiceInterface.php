<?php

namespace App\Services\SaleItem;

use App\Models\SaleItem;

interface SaleItemServiceInterface
{
    /**
     * Add one saleItem from the database.
     * @param array $data
     * @param id $id
     * @return SaleItem|null one saleItem or null if no saleItem are found.
     */
    public function addSaleItem(int $id,array $data): ?SaleItem;

    /**
     * Get all saleItem by id from the database.
     * @param array $data
     * @param id $id
     * @return array 
     */
    public function getAllSaleItemById(int $id): array;
}
