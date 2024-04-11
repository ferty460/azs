<?php require_once 'service/boot.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Авторизация | Газпром Сеть АЗС Белореченск</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
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
        </div>
    </nav>
    <section class="vh-100" style="background-color: #2779e2;">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-xl-9">

                    <h1 class="text-white mb-2">Авторизация</h1>
                    <h6 class="text-white mb-4">Еще нет учетной записи? <a href="registration.php">Зарегистрироваться</a></h6>

                    <div class="card" style="border-radius: 15px;">
                        <form action="service/do_login.php" method="post">
                            <?php flash(); ?>
                            <div class="card-body">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Электронная почта</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input type="email" class="form-control form-control-lg" placeholder="example@example.com" name="email" />

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="row align-items-center py-3">
                                    <div class="col-md-3 ps-5">

                                        <h6 class="mb-0">Пароль</h6>

                                    </div>
                                    <div class="col-md-9 pe-5">

                                        <input type="password" class="form-control form-control-lg" name="password" />

                                    </div>
                                </div>

                                <hr class="mx-n3">

                                <div class="px-5 py-4 d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Войти</button>
                                </div>

                            </div>
                        </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>