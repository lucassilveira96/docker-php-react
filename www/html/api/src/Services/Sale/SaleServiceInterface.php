<?php

namespace App\Services\Sale;

use App\Models\Sale;

interface SaleServiceInterface
{
    /**
     * Get all sales from the database.
     *
     * @return array|null The collection of sales or null if no sale are found.
     */
    public function getAllSales(): array;

    /**
     * Get one sale from the database.
     * @param int $id
     * @return Sale|null one sale or null if no sale are found.
     */
    public function getSaleById(int $id): ?Sale;

    /**
     * Create one sale from the database.
     * @param array $data
     * @return bool true|false.
     */
    public function createSale(array $data): bool;

     /**
     * Update one sale by id from the database.
     * @param int $id
     * @param array $data
     * @return Sale|null one sale or null if no sale are found.
     */
    public function updateSale(int $id, array $data): ?Sale;

    /**
     * delete one sale logical by id from the database.
     * @param int $id
     * @return bool true|false.
     */
    public function deleteSale(int $id): bool;
}
