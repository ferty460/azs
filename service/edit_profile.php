<?php

require_once 'boot.php';

// Перемещение файла в указанную папку
$stmt = pdo()->prepare("UPDATE `users` SET surname = :surname, name = :name, patronymic = :patronymic, email = :email WHERE id = :id");
$stmt->execute([
    'surname' => $_POST['surname'],
    'name' => $_POST['name'],
    'patronymic' => $_POST['patronymic'],
    'email' => $_POST['email'],
    'id' => $_SESSION['user_id']
]);

header("Location: ../profile.php");