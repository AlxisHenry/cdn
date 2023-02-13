<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="<?= $dashboard::settings() ?>" />
    <meta name="author" content="" />
    <title><?= $dashboard::settings()->getTitle() ?></title>
    <link rel="icon" type="image/x-icon" href="https://cdn.alexishenry.eu/shared/images/logo.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <?= @$asset::new("build/css/main.css"); ?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="nav">
        <div class="container">
            <div class="d-flex flex-column justify-content-center">
                <a class="navbar-brand block" href="/"><?= $dashboard::settings()->getSubtitle() ?></a>
                <p class="description text-white" <?= $dashboard::settings()->check("description") ?: 'style="display:none;"' ?>><?= $dashboard::settings()->getDescription() ?></p>
            </div>
            <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/archive">Archive</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="/upload">Upload</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href=<?= $auth ? '"?logout">Logout' : '"#login">Login' ?></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php if (!$auth) { ?>
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
    <?php } ?>