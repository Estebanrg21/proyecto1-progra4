<?php
    session_start();
    if(isset($_SESSION['verification'])){
        header("Location: dashboard.php");
    }
    require_once "database/database.php";
    require_once "models/User.php";
    [$db,$connection] = Database::getConnection();
		if(isset($_POST['id']) && isset($_POST['password'])){
			$user = new User($_POST['id'],$_POST['password']);
            $user->connection = $connection;
            if($user->login()){
                session_start();
                $_SESSION['username']=$user->username;
                $_SESSION['verification']=true;
                $_SESSION['start'] = time();
               # $_SESSION['expire'] = $_SESSION['start'] + (10 * 60);
                header("Location: /dashboard.php");
            }else{
                $loginError="Datos incorrectos";
            }
		}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body class="min-vh-100">
    <div class="card-body">
        <form role="form" action="#" method="POST">
        <?php if(isset($loginError)) : ?>
        <p class="text-danger text-xs font-weight-bolder p-0 mb-3" id="errorMessageValidate"><?php echo $loginError;?></p>
        <?php endif; ?>
        <label>Username</label>
        <div class="mb-3">
            <input type="id" name="id" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="username-addon">
        </div>
        <label>Contraseña</label>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Contraseña" aria-label="Password" aria-describedby="password-addon">
        </div>
        <div class="text-center">
            <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Iniciar sesión</button>
        </div>
        </form>
    </div>
</body>
</html>