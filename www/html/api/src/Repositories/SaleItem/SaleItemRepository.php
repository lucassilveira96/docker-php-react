<?php

namespace App\Repositories\SaleItem;

use App\Database\Connection;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\SaleItem;
use App\Services\Product\ProductService;
use PDO;

class SaleItemRepository implements SaleItemRepositoryInterface
{
    private $connection;

    /*
     * SaleItemRepository constructor.
     */
    public function __construct()
    {
        $this->connection = Connection::get();
    }

    /**
     * {@inheritDoc}
     */
    public function add(int $idSale, array $data): ?SaleItem
    {
        // Data validation (ensure all required fields are provided)
        if (!isset($data['product_id']) || !isset($data['quantity'])) {
            throw new \InvalidArgumentException("Missing necessary fields to create saleItem.");
        }

        $productService = new ProductService();

        $product = $productService->getProductById($data['product_id']);

        if (!$product) {
            throw new \InvalidArgumentException("Product not Found");
        }

        // Prepare the SQL query
        $sql = "INSERT INTO sale_items (sale_id, product_id, quantity, price_per_unit, tax_per_unit, total_price, total_tax) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $taxPerUnit = $this->calcuteTaxProduct($product->getPrice(), $product->getProductType()->getTax());
        $totalTax = $taxPerUnit * $data['quantity'];
        $totalPrice = $product->getPrice() * $data['quantity'];
        $idProduct = $product->getId();
        $price = $product->getPrice();

        try {
            $stmt = $this->connection->prepare($sql);

            // Bind parameters to the query
            $stmt->bindParam(1, $idSale, PDO::PARAM_INT);
            $stmt->bindParam(2, $idProduct, PDO::PARAM_INT);
            $stmt->bindParam(3, $data['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(4, $price, PDO::PARAM_STR);
            $stmt->bindParam(5, $taxPerUnit, PDO::PARAM_STR);
            $stmt->bindParam(6, $totalPrice, PDO::PARAM_STR);
            $stmt->bindParam(7, $totalTax, PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            // Retrieve the last inserted ID
            $id = $this->connection->lastInsertId();

            // Create a new SaleItem object and set its properties
            $saleItem = new SaleItem();
            $saleItem->setId($id);
            $saleItem->setQuantity($data['quantity']);
            $saleItem->setPricePerUnit($price);
            $saleItem->setTaxPerUnit($taxPerUnit);
            $saleItem->setTotalPrice($totalPrice);
            $saleItem->setTotalTax($totalTax);


            return $saleItem;
        } catch (\PDOException $e) {
            die;
            error_log("Failed to create a saleItem: " . $e->getMessage());
            return null;
        }
    }

    public function calcuteTaxProduct(float $price, float $tax): float
    {
        return ($price * $tax) / 100;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllByIdSale(int $id): array
    {
        $sql = "SELECT 
                    si.id, 
                    si.quantity, 
                    si.price_per_unit, 
                    si.tax_per_unit, 
                    si.total_price,
                    si.total_tax,
                    si.created_at,
                    si.updated_at,
                    si.deleted_at,
                    p.id as product_id,
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
                    FROM sale_items si 
                    INNER JOIN products p
                        ON p.id = si.product_id
                    INNER JOIN product_types pt
                        ON pt.id = p.product_type_id
                    WHERE si.sale_id = ?";

        try {
            $stmt = $this->connection->prepare($sql);

            $stmt->bindParam(1, $id, PDO::PARAM_INT);

            $stmt->execute();
            $items = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $item = $this->mapRowToSaleItem($row)->toArray();
                $items[] = $item;
            }

            return $items;
        } catch (\PDOException $e) {
            error_log("SQL Error: " . $e->getMessage());
            return [];
        }
    }

    private function mapRowToSaleItem($row): SaleItem
    {
        $productType = new ProductType();
        $productType->setId($row['product_type_id'])
            ->setDescription($row['product_type_description'])
            ->setTax($row['product_type_tax'])
            ->setCreatedAt($this->nullOrDateTime($row['product_type_created_at']))
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
            ->setProductType($productType)
            ->setCreatedAt($this->nullOrDateTime($row['product_created_at']))
            ->setUpdatedAt($this->nullOrDateTime($row['product_updated_at']))
            ->setDeletedAt($this->nullOrDateTime($row['product_deleted_at']));

        $sale = new SaleItem();
        $sale->setId($row['id'])
            ->setProduct($product)
            ->setQuantity($row['quantity'])
            ->setTotalTax($row['total_tax'])
            ->setTotalPrice($row['total_price'])
            ->setPricePerUnit($row['price_per_unit'])
            ->setTaxPerUnit($row['tax_per_unit'])
            ->setCreatedAt($this->nullOrDateTime($row['created_at']))
            ->setUpdatedAt($this->nullOrDateTime($row['updated_at']))
            ->setDeletedAt($this->nullOrDateTime($row['deleted_at']));

        return $sale;
    }

    private function nullOrDateTime($value): ?\DateTime
    {
        return $value ? new \DateTime($value) : null;
    }
}
