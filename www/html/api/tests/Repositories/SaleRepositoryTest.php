<?php

namespace App\Tests\Repositories;

use App\Database\Connection;
use PHPUnit\Framework\TestCase;
use App\Repositories\ProductType\ProductTypeRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Sale\SaleRepository;
use Faker\Factory;

class SaleRepositoryTest extends TestCase
{
    private $repository;
    private $faker;
    private $connection;
    private $productTypeRepository;
    private $productRepository;

    protected function setUp(): void
    {
        $this->productRepository = new ProductRepository();
        $this->productTypeRepository = new ProductTypeRepository();
        $this->repository = new SaleRepository();
        $this->faker = Factory::create();
        $this->connection = Connection::get();

        $sql = "DROP TABLE IF EXISTS `sales`;
        CREATE TABLE `sales` (
            `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
            `total_tax` decimal(10,2) NOT NULL DEFAULT '0.00',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
          
          DROP TABLE IF EXISTS `sale_items`;
          CREATE TABLE `sale_items` (
            `id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `sale_id` int NOT NULL,
            `product_id` int NOT NULL,
            `quantity` int NOT NULL,
            `price_per_unit` decimal(10,2) NOT NULL,
            `tax_per_unit` decimal(10,2) NOT NULL,
            `total_price` decimal(10,2) NOT NULL,
            `total_tax` decimal(10,2) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT NULL,
            `deleted_at` timestamp NULL DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";

        $this->connection->exec($sql);
    }

    private function insertTestProductType()
    {
        return $this->productTypeRepository->create([
            'description' => $this->faker->word,
            'tax' => $this->faker->randomFloat(2, 0, 100),
            'created_at' => new \DateTime(),
        ]);
    }

    private function insertTestProduct()
    {
        $productType = $this->insertTestProductType();

        return $this->productRepository->create([
            'description' => $this->faker->word,
            'ean' => $this->faker->regexify('[A-Za-z0-9]{13}'),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'purchase_price' => $this->faker->randomFloat(2, 0, 100),
            'sales_margin' => $this->faker->randomFloat(2, 0, 100),
            'quantity' => $this->faker->randomNumber(3, true),
            'minimum_quantity' => $this->faker->randomNumber(3, true),
            'product_type' => [
                "id" => $productType->getId()
            ]
        ]);
    }

    public function insertTestSale()
    {
        $product = $this->insertTestProduct();
        $productOne = $this->insertTestProduct();


        return $this->repository->create(
            [
                "products" => [
                    [
                        "quantity" => $this->faker->randomNumber(3, true),
                        "product_id" => $product->getId()
                    ],
                    [
                        "quantity" => $this->faker->randomNumber(3, true),
                        "product_id" => $productOne->getId()
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $sale = $this->insertTestSale();

        $this->assertEquals(true, $sale);
    }

    public function testGetAll()
    {
        $this->insertTestSale();
        $this->insertTestSale();

        $sales = $this->repository->getAll();
        $this->assertIsArray($sales);
        $this->assertGreaterThan(0, count($sales));
    }
}
