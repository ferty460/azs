<?php

require_once 'boot.php';

// Перемещение файла в указанную папку
$stmt = pdo()->prepare("UPDATE `product_type` SET name = :name WHERE id = :id");
$stmt->execute([
    'name' => $_POST['name'],
    'id' => $_POST['id']
]);

header("Location: ../admin.php");