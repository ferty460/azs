<?php

require_once 'boot.php';

$selectedTypes = isset($_POST['types']) ? $_POST['types'] : [];

// Build filter query based on selected types
$sql = "SELECT * FROM `product`";
if (!empty($selectedTypes)) {
    $sql .= " WHERE type_id IN (" . implode(',', $selectedTypes) . ")";
}

$stmt = pdo()->prepare($sql);
$stmt->execute();
$filteredProducts = $stmt->fetchAll();

// Generate HTML for filtered products (similar to your existing product loop)
$productHtml = "";
foreach ($filteredProducts as $product) {
    $measure = '';
    switch ($product['measure']) {
        case 'liters':
            $measure = 'л.';
            break;
        case 'tons':
            $measure = 'т.';
            break;
        case 'kg':
            $measure = 'кг.';
            break;
    }

    $productHtml .= '<div class="col-md-4 mb-4">';
    $productHtml .= '  <div class="card">';
    $productHtml .= '    <img class="card-img-top" style="height: 150px;" src="' . $product['image_path'] . '" alt="Фото товара">';
    $productHtml .= '    <div class="card-body">';
    $productHtml .= '      <h5 class="card-title">' . $product['title'] . '</h5>';
    $productHtml .= '      <p class="card-text">' . $product['description'] . '</p>';
    $productHtml .= '      <h6 class="card-subtitle mb-2 text-muted">' . $product['price'] . ' руб. за ' . $measure . '</h6>';

    // Add logic for Add to Cart button based on your existing code (optional)
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        if (in_array($product['id'], $_SESSION['cart'])) {
            $productHtml .= '        <span>Товар в корзине</span>';
        } else {
            $productHtml .= '        <form action="service/add_to_cart.php" method="post">';
            $productHtml .= '          <input type="hidden" name="product_id" value="' . $product['id'] . '">';
            $productHtml .= '          <button type="submit" class="btn btn-primary">Добавить в корзину</button>';
            $productHtml .= '        </form>';
        }
    } else {
        $productHtml .= '        <form action="service/add_to_cart.php" method="post">';
        $productHtml .= '          <input type="hidden" name="product_id" value="' . $product['id'] . '">';
        $productHtml .= '          <button type="submit" class="btn btn-primary">Добавить в корзину</button>';
        $productHtml .= '        </form>';
    }

    $productHtml .= '    </div>';
    $productHtml .= '  </div>';
    $productHtml .= '</div>';
}

echo $productHtml;
