<?php

namespace App\Services\ProductType;

use App\Models\ProductType;

interface ProductTypeServiceInterface
{
    /**
     * Get all productTypeTypes from the database.
     *
     * @return array|null The collection of productTypes or null if no productType are found.
     */
    public function getAllProductTypes(): array;

    /**
     * Get one productType from the database.
     * @param int $id
     * @return ProductType|null one productType or null if no productType are found.
     */
    public function getProductTypeById(int $id): ?ProductType;

    /**
     * Create one productType from the database.
     * @param array $data
     * @return ProductType|null one productType or null if no productType are found.
     */
    public function createProductType(array $data): ?ProductType;

    /**
     * Update one productType from the database.
     * @param int $id
     * @param array $data
     * @return ProductType|null one productType or null if no productType are found.
     */
    public function updateProductType(int $id, array $data): ?ProductType;

    /**
     * Update one productType from the database.
     * @param int $id
     * @return bool true|false
     */
    public function deleteProductType(int $id): bool;
}
