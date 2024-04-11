<?php

require_once 'boot.php';

$stmt = pdo()->prepare("INSERT INTO `product_type` (`name`) VALUES (:name)");
$stmt->execute([
    'name' => $_POST['name'],
]);

header('Location: ../admin.php');