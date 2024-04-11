<?php

require_once "boot.php";

$stmt = pdo()->prepare("INSERT INTO `orders` (`user_id`, `total_price`) VALUES (:user_id, :total_price)");
$stmt->execute([
    'user_id' => $_SESSION['user_id'],
    'total_price' => $_POST['totalSum']
]);

$orderId = pdo()->lastInsertId();

foreach ($_SESSION['cart'][$_SESSION['user_id']] as $productId) {
    $product = getProductById($productId);
    $stmt = pdo()->prepare("INSERT INTO `order_items` (`order_id`, `product_id`, `price`) VALUES (:order_id, :product_id, :price)");
    $stmt->execute([
        'order_id' => $orderId,
        'product_id' => $productId,
        'price' => $product['price']
    ]);
}

unset($_SESSION['cart'][$_SESSION['user_id']]);

header("Location: {$_SERVER['HTTP_REFERER']}"); 
