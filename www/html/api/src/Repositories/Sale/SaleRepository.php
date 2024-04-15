<?php

namespace App\Repositories\Sale;

use App\Database\Connection;
use App\Models\Sale;
use App\Services\SaleItem\SaleItemService;
use PDO;

class SaleRepository implements SaleRepositoryInterface
{
    private $connection;

    /*
     * SaleRepository constructor.
     */
    public function __construct()
    {
        $this->connection = Connection::get();
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): bool
    {
        // Data validation (ensure all required fields are provided)
        if (!isset($data['products']) || count($data['products']) < 1) {
            throw new \InvalidArgumentException("Missing necessary fields to create sale.");
        }

        // Prepare the SQL query
        $sql = "INSERT INTO sales (created_at) VALUES (NOW())";

        try {
            $stmt = $this->connection->prepare($sql);

            // Execute the query
            $stmt->execute();

            // Retrieve the last inserted ID
            $id = $this->connection->lastInsertId();

            $saleItemService = new SaleItemService();
            $totalTax = 0;
            $totalPrice = 0;


            foreach ($data['products'] as $item) {
                $saleItem = $saleItemService->addSaleItem($id, $item);
                if (isset($saleItem)) {
                    $totalTax += $saleItem->getTotalTax();
                    $totalPrice += $saleItem->getTotalPrice();
                }
            }

            // Prepare the SQL query
            $sql = "UPDATE sales 
                    SET total_amount = ?, 
                        total_tax =  ?
                    WHERE id = ?";
            try {
                $stmt = $this->connection->prepare($sql);

                // Bind parameters to the query
                $stmt->bindParam(1, $totalPrice, PDO::PARAM_INT);
                $stmt->bindParam(2, $totalTax, PDO::PARAM_INT);
                $stmt->bindParam(3, $id, PDO::PARAM_INT);

                // Execute the query
                $stmt->execute();
            } catch (\PDOException $e) {
                error_log("Failed to create a sale: " . $e->getMessage());
                return null;
            }

            return true;
        } catch (\PDOException $e) {
            error_log("Failed to create a sale: " . $e->getMessage());
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $id): ?Sale
    {
        $sql = "SELECT id, total_amount, total_tax, created_at, updated_at, deleted_at 
                FROM `sales` 
                WHERE deleted_at is null and id = ?";

        try {
            $stmt = $this->connection->prepare($sql);

            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            
            $stmt->execute();
            $sale = null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sale = $this->mapRowToSale($row);

                $saleItemService = new SaleItemService();   
                $items = $saleItemService->getAllSaleItemById((int) $row['id']);
                $sale->setItems($items);
            }

            return $sale;
        } catch (\PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, array $data): ?Sale
    {
        // Data validation (ensure all required fields are provided)
        if (empty($data['description']) || !isset($data['price']) || !isset($data['sale_type']['id'])) {
            throw new \InvalidArgumentException("Missing necessary fields to update sale.");
        }

        $sale = $this->getById($id);
        if (is_null($sale)) {
            error_log("Failed to update a sale: ");
            return null;
        }

        // Prepare the SQL query
        $sql = "UPDATE sales 
                SET description = ?,
                    price = ?,
                    sale_type_id = ?,
                    updated_at = now()
                WHERE id = ?";

        try {
            $stmt = $this->connection->prepare($sql);

            // Note: The parameters are bound in the order they appear in the SQL statement
            $stmt->bindParam(1, $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(2, $data['price'], PDO::PARAM_STR);
            $stmt->bindParam(3, $data['sale_type']['id'], PDO::PARAM_INT);
            $stmt->bindParam(4, $id, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            return $this->getById($id);
        } catch (\PDOException $e) {
            error_log("Failed to update a sale: " . $e->getMessage());
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $sale = $this->getById($id);
        if (is_null($sale)) {
            error_log("Failed to delete a sale: ");
            return false;
        }

        // Prepare the SQL query
        $sql = "UPDATE sales 
                SET deleted_at = now(),
                    updated_at = now()
                WHERE id = ?";

        try {
            $stmt = $this->connection->prepare($sql);

            // Note: The parameter are bound in the order they appear in the SQL statement
            $stmt->bindParam(1, $id, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            error_log("Failed to delete a sale: " . $e->getMessage());
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        $sql = "SELECT id, total_amount, total_tax, created_at, updated_at, deleted_at FROM `sales` WHERE deleted_at is null";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $sales = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sale = $this->mapRowToSale($row)->toArray();

                $saleItemService = new SaleItemService();   
                $items = $saleItemService->getAllSaleItemById((int) $row['id']);
                $sale['items'] = $items;

                $sales[] = $sale;
            }

            return $sales;
        } catch (\PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    private function mapRowToSale($row): Sale
    {
        $sale = new Sale();
        $sale->setId($row['id'])
            ->setTotalAmount($row['total_amount'])
            ->setTotalTax($row['total_tax'])
            ->setCreatedAt(new \DateTime($row['created_at']))
            ->setUpdatedAt($this->nullOrDateTime($row['updated_at']))
            ->setDeletedAt($this->nullOrDateTime($row['deleted_at']));

        return $sale;
    }

    private function nullOrDateTime($value): ?\DateTime
    {
        return $value ? new \DateTime($value) : null;
    }
}
