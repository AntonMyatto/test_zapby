<?php

namespace App\Entity;

/**
 * Class OrderItem
 * @package App\Entity
 */
class OrderItem
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $quantity;
    private float $price;
    private string $status;

    public function __construct(int $id, int $orderId, int $productId, int $quantity, float $price, string $status = 'pending')
    {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}