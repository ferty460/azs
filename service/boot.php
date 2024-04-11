<?php

session_start();

// PDO
function pdo(): PDO {
    static $pdo;

    if (!$pdo) {
        $config = include __DIR__.'/config.php';
        // Подключение к БД
        $dsn = 'mysql:dbname='.$config['db_name'].';host='.$config['db_host'];
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}

// flash messages
function flash(?string $message = null) {
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
          <div class="alert alert-danger mb-3">
              <?=$_SESSION['flash']?>
          </div>
        <?php }
        unset($_SESSION['flash']);
    }
}

function check_auth(): bool {
    return !!($_SESSION['user_id'] ?? false);
}

function getAllTypes() {
    $stmt = pdo()->prepare("SELECT * FROM `product_type`");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllProducts() {
    $stmt = pdo()->prepare("SELECT * FROM `product`");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProductById($productId) {
    $stmt = pdo()->prepare("SELECT * FROM `product` WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    return $stmt->fetch();
}

function getProductTypeById($productId) {
    $stmt = pdo()->prepare("SELECT * FROM `product_type` WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    return $stmt->fetch();
}

function getOrdersByUserId($id) {
    $stmt = pdo()->prepare("SELECT * FROM `orders` WHERE user_id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetchAll();
}

function getAllOrders() {
    $stmt = pdo()->prepare("SELECT * FROM `orders` ORDER BY `order_id` DESC LIMIT 5");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProductsByOrderId($orderId) {
    $stmt = pdo()->prepare("
      SELECT p.*
      FROM order_items oi
      INNER JOIN product p ON oi.product_id = p.id
      WHERE oi.order_id = :order_id
    ");
    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPriceFromOrderByProductId($orderId, $productId) {
    $stmt = pdo()->prepare("SELECT price FROM `order_items` WHERE order_id = :orderId AND product_id = :productId");
    $stmt->execute(['orderId' => $orderId, 'productId' => $productId]);
    return $stmt->fetchColumn();
}

function getMessages() {
    $stmt = pdo()->prepare("SELECT * FROM `messages` ORDER BY `id` DESC LIMIT 5");
    $stmt->execute();
    return $stmt->fetchAll();
}