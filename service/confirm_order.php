<?php

require_once 'boot.php';

$stmt = pdo()->prepare("UPDATE `orders` SET status = :status WHERE order_id = :id");
$stmt->execute([
    'status' => 'completed',
    'id' => $_POST['id']
]);

header("Location: {$_SERVER['HTTP_REFERER']}"); 