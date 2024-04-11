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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Корзина нефтепродуктов оптом. Проверьте и подтвердите выбранные вами нефтепродукты, просмотрите цены и условия оплаты. Наша цель - обеспечить надежную и удобную покупку нефтепродуктов оптом.">
    <meta name="author" content="" />
    <title>Корзина | Газпром Сеть АЗС Белореченск</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <link href="css/styles.css" rel="stylesheet" />
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
                        <a class="nav-link" href="basket.php" style="color: #ffc800;">Корзина</a>
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

    <section style="background-color: #2779e2;" class="vh-100">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-xl-9">
                    <h1 class="text-white mb-3">Корзина</h1>
                    <div class="card" style="border-radius: 15px;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php if (isset($_SESSION['cart'][$_SESSION['user_id']])) { ?>
                                    <form action="service/add_order.php" method="post">
                                        <table class="table table-striped">
                                            <thead>
                                                <th scope="col">#</th>
                                                <th scope="col">Тип</th>
                                                <th scope="col">Сорт</th>
                                                <th scope="col">Стоим. за ед.</th>
                                                <th scope="col">Количество</th>
                                                <th scope="col">Ед. изм.</th>
                                                <th scope="col">Общая стоимость</th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($_SESSION['cart'][$_SESSION['user_id']] as $i => $productId) {
                                                    $product = getProductById($productId);
                                                    $type = getProductTypeById($product['type_id']);
                                                    $measure = '';
                                                    switch ($product['measure']) {
                                                        case 'liters':
                                                            $measure = 'литры';
                                                            break;
                                                        case 'tons':
                                                            $measure = 'тонны';
                                                            break;
                                                        case 'kg':
                                                            $measure = 'кг.';
                                                            break;
                                                    }
                                                ?>
                                                    <tr id="<?php echo $productId ?>">
                                                        <td scope="row"><?php echo $i + 1; ?></td>
                                                        <td class="d-none d-sm-table-cell">
                                                            <?php echo $type['name']; ?>
                                                        </td>
                                                        <td class="d-none d-sm-table-cell">
                                                            <?php echo $product['title']; ?>
                                                        </td>
                                                        <td class="d-none d-sm-table-cell">
                                                            <span data-price="<?php echo $product['price']; ?>"><?php echo $product['price']; ?></span>
                                                            <span> ₽</span>
                                                        </td>
                                                        <td class="d-none d-sm-table-cell">
                                                            <input type="number" name="amount" class="form-control form-control-lg" value="10" data-amount="<?php echo $productId; ?>" oninput="calculateTotal(this)">
                                                        </td>
                                                        <td>
                                                            <?php echo $measure; ?>
                                                        </td>
                                                        <td class="d-none d-sm-table-cell" data-result="<?php echo $productId; ?>">
                                                            <!-- Здесь будет отображаться результат -->
                                                        </td>
                                                        <td>
                                                            <a href="service/delete_from_cart.php?product=<?php echo $productId; ?>" style="color: red; cursor: pointer;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backspace" viewBox="0 0 16 16">
                                                                    <path d="M5.83 5.146a.5.5 0 0 0 0 .708L7.975 8l-2.147 2.146a.5.5 0 0 0 .707.708l2.147-2.147 2.146 2.147a.5.5 0 0 0 .707-.708L9.39 8l2.146-2.146a.5.5 0 0 0-.707-.708L8.683 7.293 6.536 5.146a.5.5 0 0 0-.707 0z" />
                                                                    <path d="M13.683 1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-7.08a2 2 0 0 1-1.519-.698L.241 8.65a1 1 0 0 1 0-1.302L5.084 1.7A2 2 0 0 1 6.603 1zm-7.08 1a1 1 0 0 0-.76.35L1 8l4.844 5.65a1 1 0 0 0 .759.35h7.08a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
                                                                </svg>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                        <div class="px-3 py-2 d-flex justify-content-center">
                                            <input type="hidden" id="totalSum" name="totalSum" value="0">
                                            <button type="submit" class="btn btn-primary btn-lg">Оформить заказ</button>
                                        </div>
                                    </form>
                                <?php } else echo '<p class="text-center">Товаров нет</p>'; ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var inputs = document.querySelectorAll('input[name="amount"]');
            inputs.forEach(function(input) {
                calculateTotal(input);
            });
        });

        function calculateTotal(input) {
            var price = parseFloat(input.closest('tr').querySelector('[data-price]').getAttribute('data-price'));
            var amount = parseFloat(input.value);
            var result = price * amount;
            input.closest('tr').querySelector('[data-result]').textContent = result.toFixed(2) + ' ₽';

            // Обновите общее значение
            updateTotalSum();
        }

        function updateTotalSum() {
            var total = 0;
            var resultElements = document.querySelectorAll('[data-result]');
            resultElements.forEach(function(element) {
                total += parseFloat(element.textContent.replace(/[^0-9\.]+/g, ""));
            });
            document.getElementById('totalSum').value = total.toFixed(2);
        }

        function sendPostRequest(productId) {
            $.ajax({
                url: 'service/delete_from_cart.php', // Replace with your filter script
                method: 'POST',
                data: {
                    productId: productId
                }
            });
        }
        // Обновите общее значение при загрузке страницы
        document.addEventListener('DOMContentLoaded', updateTotalSum);
    </script>
</body>

</html>