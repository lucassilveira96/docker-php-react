<?php

namespace App\Services\ProductType;

use App\Models\ProductType;
use App\Repositories\ProductType\ProductTypeRepository;

class ProductTypeService implements ProductTypeServiceInterface
{
    private $productTypeRepository;

    public function __construct()
    {
        $this->productTypeRepository = new ProductTypeRepository();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllProductTypes(): array
    {
        return $this->productTypeRepository->getAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getProductTypeById(int $id): ?ProductType
    {
        return $this->productTypeRepository->getById($id);
    }

    /**
     * {@inheritDoc}
     */
    public function createProductType(array $data): ?ProductType
    {
        return $this->productTypeRepository->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function updateProductType(int $id, array $data): ?ProductType
    {
        return $this->productTypeRepository->update($id,$data);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteProductType(int $id): bool
    {
        return $this->productTypeRepository->delete($id);
    }
}
