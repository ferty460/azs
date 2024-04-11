<?php
require_once 'service/boot.php';

if (check_auth()) {
    $stmt = pdo()->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: login.php");
    die();
}

$products = getAllProducts();
$types = getAllTypes();

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 12; // Количество товаров на странице

// Вычисляем смещение для запроса к базе данных
$offset = ($currentPage - 1) * $itemsPerPage;

// Изменяем запрос к базе данных, чтобы он возвращал только товары для текущей страницы
$stmt = pdo()->prepare("SELECT * FROM product LIMIT :offset, :itemsPerPage");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Подсчет общего количества товаров
$totalItems = pdo()->query("SELECT COUNT(*) FROM product")->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Предлагаем широкий ассортимент нефтепродуктов, включая нефть, газ, масла и другие продукты. Наша цель - обеспечить нашим клиентам доступ к качественным и надежным нефтепродуктам по доступным ценам. Просмотрите наш каталог и выберите нефтепродукты, которые соответствуют вашим потребностям.">
    <meta name="author" content="" />
    <title>Каталог | Газпром Сеть АЗС Белореченск</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .candidates-listing-bg {
            padding: 210px 0px 60px 0px;
            background-size: cover;
            position: relative;
            background-position: center center;
        }

        .custom-checkbox .custom-control-input:checked~.custom-control-label:before {
            background-color: #ff4f6c;
            border-color: #ff4f6c;
        }

        .custom-checkbox .custom-control-input:focus~.custom-control-label:before {
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        .candidates-img img {
            max-width: 90px;
        }

        .fav-icon i {
            -webkit-text-stroke: 2px #ff4f6c;
            -webkit-text-fill-color: transparent;
        }

        .fav-icon i:hover {
            -webkit-text-stroke: 0px #ff4f6c;
            -webkit-text-fill-color: #ff4f6c;
        }

        .candidates-list-desc {
            overflow: hidden;
        }

        .candidates-right-details {
            position: absolute;
            top: 0;
            right: 20px;
        }

        .candidates-item-desc {
            margin-left: 45px;
        }

        .list-grid-item {
            border: 1px solid #ececec;
            border-radius: 6px;
            -webkit-transition: all 0.5s;
            transition: all 0.5s;
        }

        .list-grid-item:hover {
            -webkit-box-shadow: 0 0 20px 0px rgba(47, 47, 47, 0.09);
            box-shadow: 0 0 20px 0px rgba(47, 47, 47, 0.09);
            -webkit-transition: all 0.5s;
            transition: all 0.5s;
            border-color: transparent;
        }

        .left-sidebar .card-body {
            border-bottom: 1px solid #ececec;
        }

        .custom-control {
            margin: 11px 20px;
        }

        .card-header {
            background-color: transparent;
            margin-bottom: 0 !important;
        }

        .pagination.job-pagination .page-link {
            border-radius: 50% !important;
            margin: 0 4px;
            height: 46px;
            width: 45px;
            line-height: 29px;
            text-align: center;
            color: #777777;
        }

        .page-item.active .page-link {
            background-color: #ff4f6c;
            border-color: #ff4f6c;
            color: #ffffff !important;
        }

        .f-14 {
            font-size: 14px;
        }

        .btn-outline {
            color: #ff4f6c;
            border-color: #ff4f6c;
        }

        .btn-sm {
            padding: 8px 22px;
        }
    </style>
</head>

<body id="page-top">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="index.php">Газпром Сеть АЗС</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Меню
                <i class="fas fa-bars ms-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="catalog.php" style="color: #ffc800;">Каталог</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="basket.php">Корзина</a>
                    </li>
                    <?php if ($_SESSION['user_role'] === 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Панель администратора</a>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="service/do_logout.php">Выйти</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section style="background-color: #2779e2;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="left-sidebar">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <a data-toggle="collapse" href="#collapseOne" class="job-list" aria-expanded="true" aria-controls="collapseOne">
                                    <div class="card-header" id="headingOne">
                                        <h6 class="mb-0 text-dark f-18">Тип продукта</h6>
                                    </div>
                                </a>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
                                    <div class="card-body p-0">
                                        <?php foreach ($types as $type) { ?>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input all-select" id="customCheckAll<?php echo $type['id']; ?>" data-type-id="<?php echo $type['id']; ?>">
                                                <label class="custom-control-label ml-1 text-muted f-15" for="customCheckAll<?php echo $type['id']; ?>"><?php echo $type['name']; ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="candidates-listing-item">
                        <div>
                            <div class="row" id="product-container">
                                <!-- Карточка товара -->
                                <?php foreach ($products as $product) { ?>
                                    <?php
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
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <img class="card-img-top" style="height: 150px;" src="<?php echo $product['image_path'] ?>" alt="Фото товара">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $product['title'] ?></h5>
                                                <p class="card-text"><?php echo $product['description'] ?></p>
                                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $product['price'] ?> руб. за <?php echo $measure; ?></h6>
                                                <?php
                                                if (isset($_SESSION['cart'][$_SESSION['user_id']]) && !empty($_SESSION['cart'][$_SESSION['user_id']])) {
                                                    if (in_array($product['id'], $_SESSION['cart'][$_SESSION['user_id']])) { ?>
                                                        <span>Товар в корзине</span>
                                                    <?php } else { ?>
                                                        <form action="service/add_to_cart.php" method="post">
                                                            <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>">
                                                            <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                                                        </form>
                                                    <?php }
                                                } else { ?>
                                                    <form action="service/add_to_cart.php" method="post">
                                                        <input type="hidden" name="product_id" value="<?php echo $product['id'] ?>">
                                                        <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                                                    </form>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination job-pagination justify-content-center mt-5 mb-5">
                            <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1 ?>">
                                    <i class="mdi mdi-chevron-double-left f-15"></i>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?php echo $currentPage == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?php echo $i ?>"><?php echo $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1 ?>">
                                    <i class="mdi mdi-chevron-double-right f-15"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer py-4">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-4 my-3 my-lg-0">
                    &copy; ООО «Газпром сеть АЗС»
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            // Handle checkbox click event
            $('.all-select').click(function() {
                var selectedTypes = [];
                $('.all-select:checked').each(function() {
                    selectedTypes.push($(this).data('type-id'));
                });

                // Send AJAX request to filter products
                $.ajax({
                    url: 'service/filter_products.php', // Replace with your filter script
                    method: 'POST',
                    data: {
                        types: selectedTypes
                    }, // Send selected types as data
                    success: function(response) {
                        // Update product catalog with response (filtered products)
                        $('#product-container').html(response); // Replace with your container ID
                    }
                });
            });
        });
    </script>
</body>

</html>