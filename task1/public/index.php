<?php

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../src/Entity/Product.php';
require __DIR__ . '/../src/Entity/Order.php';
require __DIR__ . '/../src/Entity/OrderItem.php';
require __DIR__ . '/../src/Repository/ProductRepository.php';
require __DIR__ . '/../src/Repository/OrderRepository.php';
require __DIR__ . '/../src/Service/OrderService.php';

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\OrderService;

$config = require __DIR__ . '/../config/db.php';
$dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
$connection = new PDO($dsn, $config['username'], $config['password']);

$orderRepository = new OrderRepository($connection);
$productRepository = new ProductRepository($connection);
$orderService = new OrderService($orderRepository, $productRepository);

$product1 = $productRepository->findById(1); 
$product2 = $productRepository->findById(2); 

if (!$product1 || !$product2) {
    die("Товары не найдены в базе данных. Убедитесь, что товары добавлены.");
}

$orderId = $orderRepository->createOrder(0); 

$orderRepository->addOrderItem($orderId, $product1->getId(), 3, $product1->getPrice()); 
$orderRepository->addOrderItem($orderId, $product2->getId(), 2, $product2->getPrice()); 

$paidItems = [
    $product1->getId() => 1, 
    $product2->getId() => 2  
];

try {
    $result = $orderService->calculatePayment($orderId, $paidItems);
    echo "К оплате: " . $result['total_paid'] . " BYN <br>";
    echo "Оставшиеся товары:";
    foreach ($result['remaining_items'] as $item) {
        echo "Товар: " . $item['product_id'] . "<br>";
        echo "Количество: " . $item['quantity'] . " Штук. <br>";
        echo "Цена за штуку: " . $item['price'] . " BYN <br>";
        echo "Общая цена оставшихся: " . $item['price'] * $item['quantity'] . " BYN <br>";
    }
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}