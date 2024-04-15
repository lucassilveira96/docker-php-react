<?php

namespace App\Services\SaleItem;

use App\Models\SaleItem;
use App\Repositories\SaleItem\SaleItemRepository;

class SaleItemService implements SaleItemServiceInterface
{
    private $saleItemRepository;

    public function __construct()
    {
        $this->saleItemRepository = new SaleItemRepository();
    }

    /**
     * {@inheritDoc}
     */
    public function addSaleItem(int $id, array $data): ?SaleItem
    {
        return $this->saleItemRepository->add($id, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllSaleItemById(int $id): array
    {
        return $this->saleItemRepository->getAllByIdSale($id);
    }

}
