<?php

namespace App\Services\Product;

use App\Models\Product;

interface ProductServiceInterface
{
    /**
     * Get all products from the database.
     *
     * @return array|null The collection of products or null if no product are found.
     */
    public function getAllProducts(): array;

    /**
     * Get one product from the database.
     * @param int $id
     * @return Product|null one product or null if no product are found.
     */
    public function getProductById(int $id): ?Product;

    /**
     * Create one product from the database.
     * @param array $data
     * @return Product|null one product or null if no product are found.
     */
    public function createProduct(array $data): ?Product;

     /**
     * Update one product by id from the database.
     * @param int $id
     * @param array $data
     * @return Product|null one product or null if no product are found.
     */
    public function updateProduct(int $id, array $data): ?Product;

    /**
     * delete one product logical by id from the database.
     * @param int $id
     * @return bool true|false.
     */
    public function deleteProduct(int $id): bool;
}
