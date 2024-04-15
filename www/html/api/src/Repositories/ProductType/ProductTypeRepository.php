<?php

namespace App\Repositories\ProductType;

use App\Database\Connection;
use App\Models\ProductType;
use PDO;

class ProductTypeRepository implements ProductTypeRepositoryInterface
{
    private $connection;

    /*
     * ProductTypeRepository constructor.
     */
    public function __construct()
    {
        $this->connection = Connection::get();
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): ?ProductType
    {
        // Data validation (ensure all required fields are provided)
        if (empty($data['description']) || !isset($data['tax'])) {
            throw new \InvalidArgumentException("Missing necessary fields to create product type.");
        }

        // Prepare the SQL query
        $sql = "INSERT INTO product_types (description, tax) VALUES (:description, :tax)";
        
        try {
            $stmt = $this->connection->prepare($sql);

            // Bind parameters to the query
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':tax', $data['tax'], PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();
            
            // Retrieve the last inserted ID
            $id = $this->connection->lastInsertId();

            // Create a new ProductType object and set its properties
            $productType = new ProductType();
            $productType->setId($id);
            $productType->setDescription($data['description']);
            $productType->setTax($data['tax']);
            $productType->setCreatedAt(new \DateTime());
            $productType->setUpdatedAt(null);
            $productType->setDeletedAt(null);

            return $productType;
        } catch (\PDOException $e) {
            error_log("Failed to create a product type: " . $e->getMessage());
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $id): ?ProductType
    {
        $sql = "SELECT  pt.id as product_type_id,
                        pt.description as product_type_description,
                        pt.tax as product_type_tax,
                        pt.created_at as product_type_created_at,
                        pt.updated_at as product_type_updated_at,
                        pt.deleted_at as product_type_deleted_at
                FROM product_types pt
                WHERE pt.id = :id";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->mapRowToProductType($row);
            }
            return null;  
        } catch (\PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return null;  
        }
    }

    /**
     * {@inheritDoc}
     */
    public function update(int $id, array $data): ?ProductType
    {
         // Data validation (ensure all required fields are provided)
         if (empty($data['description']) || !isset($data['tax'])) {
            throw new \InvalidArgumentException("Missing necessary fields to update product.");
        }

        $productType = $this->getById($id);
        if (is_null($productType)){
            error_log("Failed to update a product type: ");
            return null;
        }

        // Prepare the SQL query
        $sql = "UPDATE product_types 
                SET description = ?,
                    tax = ?,
                    updated_at = now()
                WHERE id = ?";
        
        try {
            $stmt = $this->connection->prepare($sql);

            // Note: The parameters are bound in the order they appear in the SQL statement
            $stmt->bindParam(1, $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(2, $data['tax'], PDO::PARAM_STR);
            $stmt->bindParam(3, $id, PDO::PARAM_INT);
            
            // Execute the query
            $stmt->execute();
            
            return $this->getById($id);
        } catch (\PDOException $e) {
            error_log("Failed to update a product type: " . $e->getMessage());
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $productType = $this->getById($id);
        if (is_null($productType)){
            error_log("Failed to delete a product type: ");
            return null;
        }

        // Prepare the SQL query
        $sql = "UPDATE product_types 
                SET updated_at = now(),
                    deleted_at = now()
                WHERE id = ?";
        
        try {
            $stmt = $this->connection->prepare($sql);

            // Note: The parameters are bound in the order they appear in the SQL statement
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            
            // Execute the query
            $stmt->execute();
            
            return true;
        } catch (\PDOException $e) {
            error_log("Failed to update a product type: " . $e->getMessage());
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        $sql = "SELECT  pt.id as product_type_id,
                        pt.description as product_type_description,
                        pt.tax as product_type_tax,
                        pt.created_at as product_type_created_at,
                        pt.updated_at as product_type_updated_at,
                        pt.deleted_at as product_type_deleted_at
                FROM product_types pt";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $productTypes = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $productTypes[] = $this->mapRowToProductType($row)->toArray();
            }

            return $productTypes;
        } catch (\PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    private function mapRowToProductType($row): ProductType
    {
        $productType = new ProductType();
        $productType->setId($row['product_type_id'])
            ->setDescription($row['product_type_description'])
            ->setTax($row['product_type_tax'])
            ->setCreatedAt(new \DateTime($row['product_type_created_at']))
            ->setUpdatedAt($this->nullOrDateTime($row['product_type_updated_at']))
            ->setDeletedAt($this->nullOrDateTime($row['product_type_deleted_at']));

        return $productType;
    }

    private function nullOrDateTime($value): ?\DateTime
    {
        return $value ? new \DateTime($value) : null;
    }
}
