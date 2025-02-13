<?php

namespace App\Repository;

use App\Entity\Product;
use PDO;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return Product|null
     */
    public function findById(int $id): ?Product
    {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Product($data['id'], $data['name'], $data['price']);
    }

    /**
     * @param string $name
     * @param float $price
     * @return int
     */
    public function addProduct(string $name, float $price): int
    {
        $stmt = $this->connection->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
        $stmt->execute(['name' => $name, 'price' => $price]);
        return $this->connection->lastInsertId();
    }
}