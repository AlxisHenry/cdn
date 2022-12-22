<?php include_once '../services/dashboard.php';  auth(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?= dashboard()->title ?></title>
        <link rel="icon" type="image/x-icon" href="https://cdn.alexishenry.eu/shared/images/logo.png" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="./build/css/main.css" rel="stylesheet" />
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    </head>
    <body id="page-top">
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="nav">
            <div class="container">
                <a class="navbar-brand" href="#page-top"><?= dashboard()->description ?></a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <?php
                        foreach (getCategories() as $category) {
                            echo "<li class='nav-item mx-0 mx-lg-1'><a class='nav-link py-3 px-0 px-lg-3 rounded' href='#$category'>".ucfirst($category)."</a></li>";
                        }
                        if ($_SESSION['connected'] ?? false) { ?>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="?logout">Logout</a></li>
                        <?php } else { ?>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#login">Login</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
        <?php if (!($_SESSION['connected'] ?? false)) { ?>
        <section class='masthead page-section bg-primary-light text-white mb-0' id="login">
            <div class="container">
                <h2 class='page-section-heading text-center text-uppercase text-white'>Login</h2>
                <div class='divider-custom divider-light'>
                    <div class='divider-custom-line'></div>
                    <div class='divider-custom-icon'><i class='fas fa-star'></i></div>
                    <div class='divider-custom-line'></div>
                </div>
                <div class="container d-flex justify-content-center">
                    <form style="width: 700px; max-width: 800px;" method="POST" action="#">
                        <div class="form-group d-flex flex-column gap-1">
                            <label for="username">Username</label>
                            <input type="username" class="form-control" id="username" name="username" placeholder="Username">
                        </div>
                        <div class="form-group mt-3 d-flex flex-column gap-1">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 w-100" style="height: 50px;">Submit</button>
                    </form>
                </div>
            </div>
        </section>
        <section class='page-section' id="search">
        <?php } else { ?>
        <section class='masthead page-section' id="search">
        <?php } ?>
            <div class="container">
                <h2 class='page-section-heading text-center text-uppercase'>Search</h2>
                <div class='divider-custom'>
                    <div class='divider-custom-line'></div>
                    <div class='divider-custom-icon'><i class='fas fa-star'></i></div>
                    <div class='divider-custom-line'></div>
                </div>
                <div class="container d-flex justify-content-center mb-5 searchbar">
                    <form class="form-inline my-2 my-lg-0 d-flex gap-1" style="width: 70%;" method="GET" action="#search">
                        <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search filename" aria-label="Search" value="<?= $_GET['search'] ?? "" ?>">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </div>
                <?= searchResults() ?>
            </div>
        </section>
        <section class='page-section bg-primary-light text-white mb-0' id="latest">
            <div class="container">
                <h2 class='page-section-heading text-center text-uppercase text-white'>Latest uploads</h2>
                <div class='divider-custom divider-light'>
                    <div class='divider-custom-line'></div>
                    <div class='divider-custom-icon'><i class='fas fa-star'></i></div>
                    <div class='divider-custom-line'></div>
                </div>
                <?= latestUploads() ?>
            </div>
		</section>
        <?php
            $background = false;
            foreach (getCategories() as $category) {
                echo generateCategorySection($category, $background);
                $background = !$background;
            }
        ?>
        <div class="copyright py-4 text-center text-white">
            <div class="container"><small>&copy; <?= date('Y') . " " . dashboard()->title ?></small></div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./build/js/scripts.js"></script>
        <script src="./build/js/main.js"></script>
        <?= Swal(); ?>
    </body>
</html>
