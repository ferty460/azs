<?php

require_once 'boot.php';

$tempName = $_FILES['image_path']['tmp_name'];
$imageName = $_FILES['image_path']['name'];
$imageSize = $_FILES['image_path']['size'];
$imageType = $_FILES['image_path']['type'];

if ($imageSize > 100000000) { // 100MB
    die('File size is too large.');
}

$uniqueName = uniqid('', true) . '.' . pathinfo($imageName, PATHINFO_EXTENSION);
$uploadPath = '../assets/uploads/' . $uniqueName;

if (move_uploaded_file($tempName, $uploadPath)) {
    $stmt = pdo()->prepare("INSERT INTO `product` (`title`, `description`, `price`, `measure`, `type_id`, `image_path`) VALUES (:title, :description, :price, :measure, :type_id, :image_path)");
    $stmt->execute([
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'measure' => $_POST['measure'],
        'type_id' => $_POST['type_id'],
        'image_path' => substr($uploadPath, 3)
    ]);
}

header('Location: ../admin.php');
