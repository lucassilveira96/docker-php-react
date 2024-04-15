<?php

namespace App\Services\Sale;

use App\Models\Sale;
use App\Repositories\Sale\SaleRepository;

class SaleService implements SaleServiceInterface
{
    private $saleRepository;

    public function __construct()
    {
        $this->saleRepository = new SaleRepository();
    }

    /**
     * {@inheritDoc}
     */
    public function getAllSales(): array
    {
        return $this->saleRepository->getAll();
    }

    /**
     * {@inheritDoc}
     */
    public function getSaleById(int $id): ?Sale
    {
        return $this->saleRepository->getById($id);
    }

    /**
     * {@inheritDoc}
     */
    public function createSale(array $data): bool
    {
        return $this->saleRepository->create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function updateSale(int $id, array $data): ?Sale
    {
        return $this->saleRepository->update($id,$data);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteSale(int $id): bool
    {
        return $this->saleRepository->delete($id);
    }
}
