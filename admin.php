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

$types = getAllTypes();
$orders = getAllOrders();
$messages = getMessages();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Панель администратора для управления нефтепродуктовым складом, заказами и другими операциями. Здесь вы можете отслеживать поставки, управлять заказами, а также выполнять другие задачи, связанные с управлением нефтепродуктовым складом.">
    <meta name="author" content="" />
    <title>Панель администратора | Газпром Сеть АЗС Белореченск</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .box-part {
            background: #FFF;
            border-radius: 20px;
            padding: 20px 10px;
        }

        .hover-effect:hover {
            /* Вы можете изменить следующие свойства для достижения нужного эффекта при наведении */
            transform: scale(1.05);
            /* Увеличиваем размер */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            /* Добавляем тень */
            transition: all 0.3s ease;
            /* Добавляем плавную анимацию */
            cursor: pointer;
        }

        .hover-effect1:hover {
            /* Вы можете изменить следующие свойства для достижения нужного эффекта при наведении */
            background-color: #dbdbdb;
            /* Цвет фона при наведении */
            cursor: pointer;
            /* Изменение курсора при наведении */
            transition: background-color 0.3s ease;
            /* Плавный переход */
        }
    </style>
</head>

<body>
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
                        <a class="nav-link" href="catalog.php">Каталог</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="basket.php">Корзина</a>
                    </li>
                    <?php if ($_SESSION['user_role'] === 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php" style="color: #ffc800;">Панель администратора</a>
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
            <div class="row d-flex justify-content-center align-items-center mb-5">
                <div class="col-xl-9">
                    <h2 class="text-white mb-4">Заявки на заказ</h2>
                    <div class="card" style="border-radius: 15px;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" class="d-none d-sm-table-cell">Дата</th>
                                            <th scope="col" class="d-none d-sm-table-cell">Статус</th>
                                            <th scope="col">Общая стоимость</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $i => $order) { ?>
                                            <?php
                                            $status = '';
                                            switch ($order['status']) {
                                                case 'pending':
                                                    $status = 'Обработка';
                                                    break;
                                                case 'completed':
                                                    $status = 'Одобрено';
                                                    break;
                                                case 'cancelled':
                                                    $status = 'Отклонено';
                                                    break;
                                            }
                                            ?>
                                            <tr class="hover-effect1" data-toggle="modal" data-target="#exampleModalPr<?php echo $order['order_id']; ?>">
                                                <th scope="row"><?php echo $i + 1; ?></th>
                                                <td class="d-none d-sm-table-cell"><?php echo $order['order_date']; ?></td>
                                                <td class="d-none d-sm-table-cell"><?php echo $status; ?></td>
                                                <td><?php echo $order['total_price']; ?> ₽</td>
                                                <?php if ($order['status'] === 'pending') { ?>
                                                    <td>
                                                        <form action="service/confirm_order.php" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $order['order_id'] ?>">
                                                            <input type="submit" value="Принять" class="btn btn-success">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form action="service/cancel_order.php" method="post">
                                                            <input type="hidden" name="id" value="<?php echo $order['order_id'] ?>">
                                                            <input type="submit" value="Отклонить" class="btn btn-danger">
                                                        </form>
                                                    </td>
                                                <?php } ?>
                                            </tr>

                                            <div class="modal fade" id="exampleModalPr<?php echo $order['order_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <?php
                                                $products = getProductsByOrderId($order['order_id']);
                                                ?>
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Товары из заказа № <?php echo $i + 1; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row align-items-center py-3">
                                                                <div>

                                                                    <?php foreach ($products as $product) { ?>
                                                                        <div class="card mb-2">
                                                                            <div class="card-body">
                                                                                <b><?php echo getProductTypeById($product['type_id'])['name']; ?></b> | <?php echo $product['title']; ?> | <?php echo getPriceFromOrderByProductId($order['order_id'], $product['id']); ?> ₽
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center align-items-center mb-5">
                <div class="col-xl-9">

                    <h2 class="text-white mb-4">Добавить товар</h2>

                    <div class="card" style="border-radius: 15px;">
                        <form action="service/add_product.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">

                                <div class="row align-items-center pt-4 pb-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Название</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input type="text" class="form-control form-control-lg" name="title" />

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center pt-4 pb-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Описание</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <textarea class="form-control form-control-lg" name="description" /></textarea>

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Цена</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input type="number" step="0.01" class="form-control form-control-lg" name="price" value="0" />

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Ед. изм.</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <select class="form-select" aria-label="Default select example" name="measure">
                                            <option value="liters">Литры</option>
                                            <option value="tons">Тонны</option>
                                            <option value="kg">Килограммы</option>
                                        </select>

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Тип товара</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <select class="form-select" aria-label="Default select example" name="type_id">
                                            <?php foreach ($types as $type) { ?>
                                                <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Изображение</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile" name="image_path">
                                        </div>

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="px-5 py-4 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Добавить</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center align-items-center mb-5">
                <div class="col-xl-9 row">
                    <h2 class="text-white mb-4">Типы товаров</h2>
                    <?php foreach ($types as $type) { ?>
                        <div class="col-md-2 mb-3" data-toggle="modal" data-target="#exampleModal<?php echo $type['id']; ?>">
                            <div class="card hover-effect">
                                <div class="card-body text-center">
                                    <span><?php echo $type['name']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModal<?php echo $type['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="service/edit_type.php" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Редактирование</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row align-items-center py-3">
                                                <div class="col-md-9 pe-5">

                                                    <input type="hidden" name="id" value="<?php echo $type['id']; ?>">
                                                    <input type="text" class="form-control form-control-lg" placeholder="Название" name="name" value="<?php echo $type['name']; ?>" />

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                            <button type="submit" class="btn btn-primary">Изменить</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="px-5 py-4 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModal">Добавить</button>
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="service/add_type.php" method="post">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Добавление нового типа товара</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row align-items-center py-3">
                                            <div class="col-md-9 pe-5">

                                                <input type="text" class="form-control form-control-lg" placeholder="Название" name="name" />

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                        <button type="submit" class="btn btn-primary">Добавить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center align-items-center mb-5">
                <div class="col-xl-9">
                    <h2 class="text-white mb-4">Обратная связь</h2>
                    <div class="card" style="border-radius: 15px;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col" class="d-none d-sm-table-cell">Имя</th>
                                            <th scope="col" class="d-none d-sm-table-cell">Почта</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($messages as $i => $order) { ?>

                                            <tr class="hover-effect1" data-toggle="modal" data-target="#exampleModalM<?php echo $order['id']; ?>">
                                                <th scope="row"><?php echo $i + 1; ?></th>
                                                <td class="d-none d-sm-table-cell"><?php echo $order['name']; ?></td>
                                                <td class="d-none d-sm-table-cell"><?php echo $order['email']; ?></td>
                                            </tr>

                                            <div class="modal fade" id="exampleModalM<?php echo $order['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Сообщение № <?php echo $i + 1; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row align-items-center py-3">
                                                                <div>

                                                                    <div class="card mb-2">
                                                                        <div class="card-body">
                                                                            <p>Имя: <b><?php echo $order['name']; ?></b></p>
                                                                            <p>Почта: <b><?php echo $order['email']; ?></b></p>
                                                                            <p>Номер телефона: <b><?php echo $order['phone']; ?></b></p>
                                                                            <p>Сообщение: <?php echo $order['message']; ?></p>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>