<?php
session_start();
if (isset($_SESSION['verification'])) {
    header("Location: dashboard.php");
}
require_once "database/database.php";
require_once "models/User.php";
[$db, $connection] = Database::getConnection();
if (isset($_POST['id']) && isset($_POST['password'])) {
    $user = new User($_POST['id'], $_POST['password']);
    $user->connection = $connection;
    if ($user->login()) {
        session_start();
        $_SESSION['username'] = $user->username;
        $_SESSION['verification'] = true;
        $_SESSION['start'] = time();
        $_SESSION['expire'] = $_SESSION['start'] + (10 * 60);
        header("Location: /dashboard.php");
    } else {
        $loginError = "Datos incorrectos";
    }
}
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
    <title>UEFA Champions Leage Management: Login</title>
</head>

<body class="min-vh-100">

    <div class="col-12 vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="d-flex justify-content-between align-items-center pt-4 mb-4">
            <div class="ms-3">
                <a href="/index.php">
                    <img src="/assets/images/ucl-logo.svg" alt="ucl-image" width="100%" height="100%" style="filter: brightness(0) invert(1);">
                </a>
            </div>
        </div>
        <div class=" col-xl-4 col-12">
            <div class="card-header pb-0 p-3 border-0 d-flex align-items-center bg-transparent">
            </div>
            <div class="card-body p-3">
                <form role="form" action="#" method="POST"  style="font-family: 'Roboto', sans-serif !important;">
                    <?php if (isset($loginError)) : ?>
                        <p class="text-danger text-xs font-weight-bolder p-0 mb-3" id="errorMessageValidate"><?php echo $loginError; ?></p>
                    <?php endif; ?>

                    <div class="mb-3">
                        <h6 class="text-uppercase text-white text-xs font-weight-bolder">Username</h6>
                        <div>
                        <input type="text" autocomplete="new-password" style="border-bottom-left-radius:0 !important;border-top-left-radius:0 !important;" class="bg-transparent c-input-dark form-control" id="id" placeholder="Username" name="id" aria-label="Name" aria-describedby="text-addon" value="">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                    <h6 class="text-uppercase text-white text-xs font-weight-bolder">Password</h6>
                        <input type="password" style="border-bottom-left-radius:0 !important;border-top-left-radius:0 !important;"  name="password" class="bg-transparent c-input-dark form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                    </div>
                    <div class="text-center w-40" style="margin:0 auto;">
                        <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>