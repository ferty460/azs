<?php

require_once "boot.php";

$productId = $_POST['product_id'];

$stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['cart'][$_SESSION['user_id']])) {
    $_SESSION['cart'][$_SESSION['user_id']] = array();
}

// Проверяем, существует ли товар в корзине пользователя
if (!in_array($productId, $_SESSION['cart'][$_SESSION['user_id']])) {
    // Добавляем товар в корзину пользователя
    $_SESSION['cart'][$_SESSION['user_id']][] = $productId;
}

header("Location: {$_SERVER['HTTP_REFERER']}");
