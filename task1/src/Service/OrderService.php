<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;

/**
 * Class OrderService
 * @package App\Service
 */
class OrderService
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param int $orderId
     * @param array $paidItems 
     * @return array
     */
    public function calculatePayment(int $orderId, array $paidItems): array
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new \InvalidArgumentException("Order not found");
        }

        $totalPaid = 0;
        $remainingItems = [];

        foreach ($order->getItems() as $item) {
            if (isset($paidItems[$item->getProductId()])) {
                $paidQuantity = $paidItems[$item->getProductId()];
                $totalPaid += $item->getPrice() * $paidQuantity;

                if ($paidQuantity < $item->getQuantity()) {
                    $remainingItems[] = [
                        'product_id' => $item->getProductId(),
                        'quantity' => $item->getQuantity() - $paidQuantity,
                        'price' => $item->getPrice()
                    ];
                }

                $item->setStatus('paid');
            } else {
                $remainingItems[] = [
                    'product_id' => $item->getProductId(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice()
                ];
            }
        }

        $this->orderRepository->save($order);

        return [
            'total_paid' => $totalPaid,
            'remaining_items' => $remainingItems
        ];
    }
}