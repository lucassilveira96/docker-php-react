<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;

class ProductService implements ProductServiceInterface
{
    private $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllProducts(): array
    {
        return $this->productRepository->getAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->getById($id);
    }

    /**
     * {@inheritDoc}
     */
    public function createProduct(array $data): ?Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function updateProduct(int $id, array $data): ?Product
    {
        return $this->productRepository->update($id,$data);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }
}
