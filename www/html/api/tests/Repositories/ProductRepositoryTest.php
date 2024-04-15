<?php

namespace App\Tests\Repositories;

use App\Database\Connection;
use App\Models\Product;
use PHPUnit\Framework\TestCase;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductType\ProductTypeRepository;
use Faker\Factory;

class ProductRepositoryTest extends TestCase
{
    private $repository;
    private $faker;
    private $connection;
    private $productTypeRepository;

    protected function setUp(): void
    {
        $this->repository = new ProductRepository();
        $this->productTypeRepository = new ProductTypeRepository();
        $this->faker = Factory::create();
        $this->connection = Connection::get();

        $sql = "DROP TABLE IF EXISTS `products`;
        CREATE TABLE `products` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `description` varchar(255) NOT NULL,
            `price` decimal(10,2) NOT NULL,
            `product_type_id` int NOT NULL,
            `ean` varchar(13) NOT NULL,
            `purchase_price` decimal(10,2) NOT NULL,
            `sales_margin` decimal(10,2) NOT NULL,
            `quantity` int NOT NULL,
            `minimum_quantity` int NOT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL,
            `deleted_at` datetime DEFAULT NULL
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

        return $this->repository->create([
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

    public function testCreateProduct()
    {
        $productType = $this->insertTestProductType();

        $data = [
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
        ];

        $product = $this->repository->create($data);

        $this->assertEquals($data['description'], $product->getDescription());
        $this->assertEquals($data['price'], $product->getPrice());
    }

    public function testGetById()
    {
        $insertedProduct = $this->insertTestProduct();
        $product = $this->repository->getById($insertedProduct->getId());

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($insertedProduct->getDescription(), $product->getDescription());
    }

    public function testUpdate()
    {
        $productType = $this->insertTestProductType();
        $insertTestProduct = $this->insertTestProduct();

        $newData = [
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
        ];

        $updatedProduct = $this->repository->update($insertTestProduct->getId(), $newData);
        $this->assertInstanceOf(Product::class, $updatedProduct);
        $this->assertEquals($newData['description'], $updatedProduct->getDescription());
    }

    public function testDelete()
    {
        $insertedProduct = $this->insertTestProduct();
        $result = $this->repository->delete($insertedProduct->getId());
        $this->assertTrue($result);
    }

    public function testGetAll()
    {
        $this->insertTestProduct();
        $this->insertTestProduct();

        $products = $this->repository->getAll();
        $this->assertIsArray($products);
        $this->assertGreaterThan(0, count($products));
    }

}
