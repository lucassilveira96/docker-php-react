<?php

namespace App\Repositories\Product;

use App\Models\Product;

interface ProductRepositoryInterface
{
    /**
     * Insert new product into database
     */
    public function create(array $data): ?Product;

    /**
     * get one product by id into database
     */
    public function getById(int $id): ?Product;

    /**
     * Update one product by id into database
     */
    public function update(int $id, array $data): ?Product;

    /**
     * delete one product by id into database
     */
    public function delete(int $id): ?bool;

    /**
     * get all products into database
     */
    public function getAll(): ?array;
}
