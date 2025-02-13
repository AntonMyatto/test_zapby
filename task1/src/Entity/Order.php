<?php

namespace App\Entity;

/**
 * Class Order
 * @package App\Entity
 */
class Order
{
    private int $id;
    private float $totalPrice;
    /** @var OrderItem[] */
    private array $items;

    public function __construct(int $id, float $totalPrice, array $items = [])
    {
        $this->id = $id;
        $this->totalPrice = $totalPrice;
        $this->items = $items;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): void
    {
        $this->items[] = $item;
    }
}