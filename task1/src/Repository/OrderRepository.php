<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderItem;
use PDO;

/**
 * Class OrderRepository
 * @package App\Repository
 */
class OrderRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int $id
     * @return Order|null
     */
    public function findById(int $id): ?Order
    {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $orderData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$orderData) {
            return null;
        }

        $order = new Order($orderData['id'], $orderData['total_price']);

        $stmt = $this->connection->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmt->execute(['order_id' => $id]);
        $itemsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($itemsData as $itemData) {
            $order->addItem(new OrderItem(
                $itemData['id'],
                $itemData['order_id'],
                $itemData['product_id'],
                $itemData['quantity'],
                $itemData['price'],
                $itemData['status']
            ));
        }

        return $order;
    }

    /**
     * @param Order $order
     */
    public function save(Order $order): void
    {
        $stmt = $this->connection->prepare("UPDATE orders SET total_price = :total_price WHERE id = :id");
        $stmt->execute([
            'id' => $order->getId(),
            'total_price' => $order->getTotalPrice()
        ]);

        foreach ($order->getItems() as $item) {
            $stmt = $this->connection->prepare("UPDATE order_items SET status = :status WHERE id = :id");
            $stmt->execute([
                'id' => $item->getId(),
                'status' => $item->getStatus()
            ]);
        }
    }

    /**
     * @param float $totalPrice
     * @return int
     */
    public function createOrder(float $totalPrice): int
    {
        $stmt = $this->connection->prepare("INSERT INTO orders (total_price) VALUES (:total_price)");
        $stmt->execute(['total_price' => $totalPrice]);
        return $this->connection->lastInsertId();
    }

    /**
     * @param int $orderId
     * @param int $productId
     * @param int $quantity
     * @param float $price
     * @return int
     */
    public function addOrderItem(int $orderId, int $productId, int $quantity, float $price): int
    {
        $stmt = $this->connection->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        $stmt->execute([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
        return $this->connection->lastInsertId();
    }
}