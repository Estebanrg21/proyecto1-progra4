<?php
session_start();
if (!$_SESSION['verification']) {
    header("Location: /index.php");
}

$now = time();

if ($now > $_SESSION['expire']) {
    session_destroy();
    header("Location: /index.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body class="g-sidenav-show min-vh-100">
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
                <a class="nav-link" href="/dashboard.php">
                    <span class="nav-link-text ms-1">Inicio</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link  active" href="../dashboard/sections-admin.php">
                    <span class="nav-link-text ms-1 text-wrap">Administrar equipos</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="../dashboard/food-times-admin.php">

                    <span class="nav-link-text ms-1 text-wrap">Administrar paises</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="../dashboard/students-admin.php">
                    <span class="nav-link-text ms-1 text-wrap">Administrar sorteo</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link " href="../dashboard/students-admin.php">
                    <span class="nav-link-text ms-1 text-wrap">Administrar resultados</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Acciones de cuenta</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link  " href="../logout.php">
                    <span class="nav-link-text ms-1">Cerrar sesión</span>
                </a>
            </li>
        </ul>


    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <h2 class="font-weight-bolder mb-0 text-light">Administración de equipos</h2>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link  font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"><?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : "") ?></span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link  p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
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
            <div class="row mt-4">
                <div class="col-12 col-xl-4 ">
                    <div class="card h-100 bg-cdark">
                        <div class="card-body p-3">
                            <form role="form" method="POST" action="#" id="mainForm" style="font-family: 'Roboto', sans-serif !important;">
                                <?php if (isset($errorSubmission)) : ?>
                                    <p class="text-danger text-xs font-weight-bolder mb-3" id="errorMessageMainForm"><?php echo $errorSubmission; ?></p>
                                <?php endif; ?>

                                <div class="mb-3 ">
                                    <h6 class="text-uppercase  text-xs font-weight-bolder">Nombre</h6>
                                    <div>
                                        <input type="text"  autocomplete="new-password" class=" form-control dark-input form-outline" id="mainFormName" placeholder="Nombre" name="name" aria-label="Nombre" aria-describedby="text-addon" value="<?php echo (isset($name) ? $name : "")  ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-uppercase  text-xs font-weight-bolder">Descripción</h6>
                                    <div>
                                        <textarea class="form-control" id="formDescription" name="description" rows="3"><?php echo (isset($description) ? $description : "")  ?></textarea>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" id="mainFormButton" class="btn bg-gradient-info w-100 mt-4 mb-0"><?php echo (isset($formButtonText) ? $formButtonText : "Crear") ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="../assets/js/toggleSidebar.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
</body>

</html>