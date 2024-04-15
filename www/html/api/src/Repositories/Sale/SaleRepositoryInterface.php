<?php

namespace App\Repositories\Sale;

use App\Models\Sale;

interface SaleRepositoryInterface
{
    /**
     * Insert new sale into database
     */
    public function create(array $data): bool;

    /**
     * get one sale by id into database
     */
    public function getById(int $id): ?Sale;

    /**
     * Update one sale by id into database
     */
    public function update(int $id, array $data): ?Sale;

    /**
     * delete one sale by id into database
     */
    public function delete(int $id): ?bool;

    /**
     * get all sales into database
     */
    public function getAll(): ?array;
}
