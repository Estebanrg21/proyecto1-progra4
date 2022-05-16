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

<body class="">
<?php
    $login = "/login.php";
    $dashboard = "/dashboard.php";
    $mainLink = (!isset($_SESSION['verification']))?$login:$dashboard;
    $linkText = (!isset($_SESSION['verification']))?"Login":"Panel de control";
?>
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
                            <a href="<?php echo (isset($mainLink)?$mainLink:"") ?>" class="nav-link  font-weight-bold px-0 text-white">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"><?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : (isset($linkText)?$linkText:"") ) ?></span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
</main>


</body>

</html>