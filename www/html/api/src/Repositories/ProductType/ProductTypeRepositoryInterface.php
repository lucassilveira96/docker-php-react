<?php

namespace App\Repositories\ProductType;

use App\Models\ProductType;

interface ProductTypeRepositoryInterface
{
    /**
     * Insert new productType into database
     */
    public function create(array $data): ?ProductType;

    /**
     * get one productType by id into database
     */
    public function getById(int $id): ?ProductType;

    /**
     * Update one productType by id into database
     */
    public function update(int $id, array $data): ?ProductType;

    /**
     * delete one productType by id into database
     */
    public function delete(int $id): bool;

    /**
     * get all productTypes into database
     */
    public function getAll(): ?array;
}
