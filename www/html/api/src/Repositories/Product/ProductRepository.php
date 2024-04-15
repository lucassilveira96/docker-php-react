<?php

namespace App\Repositories\Product;

use App\Database\Connection;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\ProductType\ProductTypeService;
use PDO;

class ProductRepository implements ProductRepositoryInterface
{
    private $connection;

    /*
     * ProductRepository constructor.
     */
    public function __construct()
    {
        $this->connection = Connection::get();
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $data): ?Product
    {
        // Data validation (ensure all required fields are provided)
        if (
            empty($data['description'])
            || empty($data['ean'])
            || empty($data['purchase_price'])
            || empty($data['sales_margin'])
            || empty($data['quantity'])
            || empty($data['minimum_quantity'])
            || !isset($data['price'])
            || !isset($data['product_type']['id'])
        ) {
            throw new \InvalidArgumentException("Missing necessary fields to create product.");
        }

        // Prepare the SQL query
        $sql = "INSERT INTO products (description, 
                                      price, 
                                      product_type_id,
                                      ean,
                                      purchase_price,
                                      sales_margin,
                                      quantity,
                                      minimum_quantity, 
                                      created_at) 
                                      VALUES (:description,
                                              :price, 
                                              :productTypeId,
                                              :ean,
                                              :purchasePrice,
                                              :salesMargin,
                                              :quantity,
                                              :minimumQuantity,
                                               NOW())";

        try {
            $stmt = $this->connection->prepare($sql);

            // Bind parameters to the query
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':price', $data['price'], PDO::PARAM_STR);
            $stmt->bindParam(':productTypeId', $data['product_type']['id'], PDO::PARAM_INT);
            $stmt->bindParam(':ean', $data['ean'], PDO::PARAM_STR);
            $stmt->bindParam(':purchasePrice', $data['purchase_price'], PDO::PARAM_STR);
            $stmt->bindParam(':salesMargin', $data['sales_margin'], PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':minimumQuantity', $data['minimum_quantity'], PDO::PARAM_INT);
            

            // Execute the query
            $stmt->execute();

            // Retrieve the last inserted ID
            $id = $this->connection->lastInsertId();

            $productTypeService = new ProductTypeService();
            $productType = $productTypeService->getProductTypeById((int) $data['product_type']['id']);

            // Create a new Product object and set its properties
            $product = new Product();
            $product->setId($id);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $product->setEan($data['ean']);
            $product->setPurchasePrice($data['purchase_price']);
            $product->setSalesMargin($data['sales_margin']);
            $product->setQuantity($data['quantity']);
            $product->setMinimumQuantity($data['minimum_quantity']);
            $product->setCreatedAt(new \DateTime());
            $product->setProductType($productType);
            $product->setDeletedAt(null);
            $product->setUpdatedAt(null);

            return $product;
        } catch (\PDOException $e) {
            var_dump($e->getMessage());
            die;
            error_log("Failed to create a product: " . $e->getMessage());
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getById(int $id): ?Product
    {
        $sql = "SELECT  p.id as product_id,
                        p.description as product_description,
                        p.price as product_price,
                        p.created_at as product_created_at,
                        p.updated_at as product_updated_at,
                        p.deleted_at as product_deleted_at,
                        p.ean as product_ean,
                        p.purchase_price product_purchase_price,
                        p.sales_margin as product_sales_margin,
                        p.quantity as product_quantity,
                        p.minimum_quantity as product_minimum_quantity,
                        pt.id as product_type_id,
                        pt.description as product_type_description,
                        pt.tax as product_type_tax,
                        pt.created_at as product_type_created_at,
                        pt.updated_at as product_type_updated_at,
                        pt.deleted_at as product_type_deleted_at
                FROM products p
                INNER JOIN product_types pt ON pt.id = p.product_type_id
                WHERE p.id = :id and p.deleted_at is null";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->mapRowToProduct($row);
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
    public function update(int $id, array $data): ?Product
    {
        // Data validation (ensure all required fields are provided)
        if (empty($data['description']) || !isset($data['price']) || !isset($data['product_type']['id'])) {
            throw new \InvalidArgumentException("Missing necessary fields to update product.");
        }

        $product = $this->getById($id);
        if (is_null($product)) {
            error_log("Failed to update a product: ");
            return null;
        }

        // Prepare the SQL query
        $sql = "UPDATE products 
                SET description = ?,
                    price = ?,
                    product_type_id = ?,
                    ean = ?,
                    purchase_price = ?,
                    sales_margin = ?,
                    quantity = ?,
                    minimum_quantity = ?,
                    updated_at = now()
                WHERE id = ?";

        try {
            $stmt = $this->connection->prepare($sql);

            // Note: The parameters are bound in the order they appear in the SQL statement
            $stmt->bindParam(1, $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(2, $data['price'], PDO::PARAM_STR);
            $stmt->bindParam(3, $data['product_type']['id'], PDO::PARAM_INT);
            $stmt->bindParam(4, $data['ean'], PDO::PARAM_STR);
            $stmt->bindParam(5, $data['purchase_price'], PDO::PARAM_STR);
            $stmt->bindParam(6, $data['sales_margin'], PDO::PARAM_STR);
            $stmt->bindParam(7, $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(8, $data['minimum_quantity'], PDO::PARAM_INT);
            $stmt->bindParam(9, $id, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();

            return $this->getById($id);
        } catch (\PDOException $e) {
            error_log("Failed to update a product: " . $e->getMessage());
            return null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(int $id): bool
    {
        $product = $this->getById($id);
        if (is_null($product)) {
            error_log("Failed to delete a product: ");
            return false;
        }

        // Prepare the SQL query
        $sql = "UPDATE products 
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
            error_log("Failed to delete a product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAll(): array
    {
        $sql = "SELECT  p.id as product_id,
                        p.description as product_description,
                        p.price as product_price,
                        p.ean as product_ean,
                        p.purchase_price product_purchase_price,
                        p.sales_margin as product_sales_margin,
                        p.quantity as product_quantity,
                        p.minimum_quantity as product_minimum_quantity,
                        p.created_at as product_created_at,
                        p.updated_at as product_updated_at,
                        p.deleted_at as product_deleted_at,
                        pt.id as product_type_id,
                        pt.description as product_type_description,
                        pt.tax as product_type_tax,
                        pt.created_at as product_type_created_at,
                        pt.updated_at as product_type_updated_at,
                        pt.deleted_at as product_type_deleted_at
                FROM products p
                INNER JOIN product_types pt ON pt.id = p.product_type_id
                WHERE p.deleted_at is null";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $products = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $products[] = $this->mapRowToProduct($row)->toArray();
            }

            return $products;
        } catch (\PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    private function mapRowToProduct($row): Product
    {
        $productType = new ProductType();
        $productType->setId($row['product_type_id'])
            ->setDescription($row['product_type_description'])
            ->setTax($row['product_type_tax'])
            ->setCreatedAt(new \DateTime($row['product_type_created_at']))
            ->setUpdatedAt($this->nullOrDateTime($row['product_type_updated_at']))
            ->setDeletedAt($this->nullOrDateTime($row['product_type_deleted_at']));

        $product = new Product();
        $product->setId($row['product_id'])
            ->setDescription($row['product_description'])
            ->setPrice($row['product_price'])
            ->setEan($row['product_ean'])
            ->setPurchasePrice($row['product_purchase_price'])
            ->setSalesMargin($row['product_sales_margin'])
            ->setQuantity($row['product_quantity'])
            ->setMinimumQuantity($row['product_minimum_quantity'])
            ->setCreatedAt(new \DateTime($row['product_created_at']))
            ->setUpdatedAt($this->nullOrDateTime($row['product_updated_at']))
            ->setDeletedAt($this->nullOrDateTime($row['product_deleted_at']))
            ->setProductType($productType);

        return $product;
    }

    private function nullOrDateTime($value): ?\DateTime
    {
        return $value ? new \DateTime($value) : null;
    }
}
