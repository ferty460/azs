<?php

require_once "boot.php";

$stmt = pdo()->prepare("INSERT INTO `messages` (`name`, `email`, `phone`, `message`) VALUES (:name, :email, :phone, :message)");
$stmt->execute([
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'phone' => $_POST['phone'],
    'message' => $_POST['message']
]);

header('Location: ../index.php');