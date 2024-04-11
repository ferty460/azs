<?php

require_once "boot.php";

$productId = $_GET['product'];


$_SESSION['cart'][$_SESSION['user_id']] = array_diff($_SESSION['cart'][$_SESSION['user_id']], [$productId]);

if (empty($_SESSION['cart'][$_SESSION['user_id']])) unset($_SESSION['cart'][$_SESSION['user_id']]);


header("Location: {$_SERVER['HTTP_REFERER']}");