<?php
session_start();
if (!$_SESSION['verification']) {
    header("Location: /index.php");
}
/*
$now = time();

if ($now > $_SESSION['expire']) {
    session_destroy();
    header("Location: /index.php");
}*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UEFA Champions Leage Management</title>
</head>

<body class="g-sidenav-show ">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-cdark" id="sidenav-main" style="z-index:99;">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="/">
                <img src="https://img.uefa.com/imgml/uefacom/ucl/2021/logos/logo_dark.svg" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold text-light">Management console</span>
            </a>
        </div>
        <hr class="horizontal light mt-0">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($option) ? (($option==0)?"active":"") : "") ?>" href="/dashboard.php">
                    <span class="nav-link-text ms-1">Start</span>
                </a>
            </li>

            

            <li class="nav-item">
                <a class="nav-link <?php echo (isset($option) ? (($option==1)?"active":"") : "") ?>" href="../dashboard/country-admin.php">

                    <span class="nav-link-text ms-1 text-wrap">Country management</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (isset($option) ? (($option==2)?"active":"") : "") ?>" href="../dashboard/teams-admin.php">
                    <span class="nav-link-text ms-1 text-wrap">Team management</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (isset($option) ? (($option==3)?"active":"") : "") ?>" href="../dashboard/draws-admin.php">
                    <span class="nav-link-text ms-1 text-wrap">Draw</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo (isset($option) ? (($option==4)?"active":"") : "") ?>" href="../dashboard/results-admin.php">
                    <span class="nav-link-text ms-1 text-wrap">Results management</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account actions</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link  " href="../logout.php">
                    <span class="nav-link-text ms-1">Cerrar sesión</span>
                </a>
            </li>
        </ul>


    </aside>
    <main class="main-content position-relative h-100 border-radius-lg ">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl py-0 pt-3" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <img src="/assets/images/ucl-logo.svg" alt="ucl-image" width="100%" height="100%" style="filter: brightness(0) invert(1);">
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center" id="navbar">

                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link  font-weight-bold px-0 text-white">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"><?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : "") ?></span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link  p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner" style="filter: brightness(0) invert(1);">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid py-4">