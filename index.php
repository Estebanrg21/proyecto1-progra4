<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="/css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto 1</title>
</head>

<body>

<?php
    $login = "/login.php";
    $dashboard = "/dashboard.php";
    $mainLink = (!isset($_SESSION['verification']))?$login:$dashboard;
    $linkText = (!isset($_SESSION['verification']))?"Login":"Panel de control";
?>
    <nav>
        <div>
            <a href="<?php echo (isset($mainLink)?$mainLink:"") ?>"><?php echo (isset($linkText)?$linkText:"")  ?></a>
        </div>
    </nav>

</body>

</html>