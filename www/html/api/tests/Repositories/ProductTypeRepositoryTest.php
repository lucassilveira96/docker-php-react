<?php

namespace App\Tests\Repositories;

use App\Database\Connection;
use PHPUnit\Framework\TestCase;
use App\Repositories\ProductType\ProductTypeRepository;
use App\Models\ProductType;
use Faker\Factory;

class ProductTypeRepositoryTest extends TestCase
{
    private $repository;
    private $faker;
    private $connection;

    protected function setUp(): void
    {
        $this->repository = new ProductTypeRepository(); 
        $this->faker = Factory::create();
        $this->connection = Connection::get();

        $sql = "DROP TABLE IF EXISTS `product_types`;
        CREATE TABLE `product_types` (
          `id` int NOT NULL AUTO_INCREMENT,
          `description` varchar(255) NOT NULL,
          `tax` decimal(10,2) NOT NULL,
          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` datetime DEFAULT NULL,
          `deleted_at` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";

        $this->connection->exec($sql);

    }

    public function insertTestProductType()
    {
        return $this->repository->create([
            'description' => $this->faker->word,
            'tax' => $this->faker->randomFloat(2, 0, 100),
            'created_at' => new \DateTime(),
        ]);
    }

    public function testCreate()
    {
        $data = [
            'description' => $this->faker->word,
            'tax' => $this->faker->randomFloat(2, 0, 100),
            'created_at' => new \DateTime(),
        ];

        $productType = $this->repository->create($data);

        $this->assertEquals($data['description'], $productType->getDescription());
        $this->assertEquals($data['tax'], $productType->getTax());
    }

    public function testGetById()
    {
        $insertedProductType = $this->insertTestProductType();
        $productType = $this->repository->getById($insertedProductType->getId());

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertEquals($insertedProductType->getDescription(), $productType->getDescription());
    }

    public function testUpdate()
    {
        $insertedProductType = $this->insertTestProductType();
        $newData = [
            'description' => $this->faker->word,
            'tax' => $this->faker->randomFloat(2, 0, 100)
        ];

        $updatedProductType = $this->repository->update($insertedProductType->getId(), $newData);
        $this->assertInstanceOf(ProductType::class, $updatedProductType);
        $this->assertEquals($newData['description'], $updatedProductType->getDescription());
    }

    public function testDelete()
    {
        $insertedProductType = $this->insertTestProductType();
        $result = $this->repository->delete($insertedProductType->getId());
        $this->assertTrue($result);
    }

    public function testGetAll()
    {
        $this->insertTestProductType();
        $this->insertTestProductType();

        $productTypes = $this->repository->getAll();
        $this->assertIsArray($productTypes);
        $this->assertGreaterThan(0, count($productTypes));
    }


    
}
