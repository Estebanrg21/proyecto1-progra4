<?php
require_once(__DIR__ . "/database/database.php");
require_once(__DIR__ . "/models/Team.php");
[$db, $connection] = Database::getConnection();
$groups = Team::getGroupsStatus($connection);
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

<body class="">
    <?php
    session_start();
    $login = "/login.php";
    $dashboard = "/dashboard.php";
    $mainLink = (!isset($_SESSION['verification'])) ? $login : $dashboard;
    $linkText = (!isset($_SESSION['verification'])) ? "Login" : "Control panel";
    ?>
    <main class="main-content position-relative h-100 border-radius-lg ">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl py-0 pt-3" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <a href="/index.php">
                            <img src="/assets/images/ucl-logo.svg" alt="ucl-image" width="100%" height="100%" style="filter: brightness(0) invert(1);">
                        </a>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center" id="navbar">

                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="<?php echo (isset($mainLink) ? $mainLink : "") ?>" class="nav-link  font-weight-bold px-0 text-white">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"><?php echo  (isset($linkText) ? $linkText : "") ?></span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
        <div class=" mt-4 container">
            <div class="row justify-content-center ">
                <?php
                if (isset($groups)) {
                    foreach ($groups as $group => $teams) {

                        echo "
                        <div class=\"col-lg-6 col-md-12\">
                        <div class=\"card mb-4 bg-cdark\">
                            <div class=\"card-header pb-0 bg-cdark\">
                                <h6 class=\"text-white\"> Group " . $group . "</h6>
                            </div>
                            <div class=\"card-body px-0 pt-0 pb-2\">
                                <div class=\"table-responsive p-0\">
                                    <table class=\"table align-items-center mb-0\" style=\"font-family: 'Roboto', sans-serif !important;\">
                                        <thead>
                                            <tr>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 \">Team</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">MP</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">W</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">D</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">L</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">GF</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">GA</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">GD</th>
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">Pts</th>
                                            </tr>
                                        </thead>
                                        <tbody>";
                        foreach ($teams as $row) {
                            echo    "<tr>
                                                <td class=\"align-middle text-center text-sm\">
                                                        <p class=\"text-xs font-weight-bold mb-0 \">" . $row["name"] . "</p>
                                                </td>  
                                                <td class=\"align-middle text-center text-sm\">
                                                        <p class=\"text-xs font-weight-bold mb-0 \">" . $row["mp"] . "</p>
                                                </td>
                                                <td class=\"align-middle text-center text-sm\">
                                                    <p class=\"text-xs font-weight-bold mb-0 \">" . $row["matches_win"] . "</p>
                                                </td>
                                                <td class=\"align-middle text-center text-sm\">
                                                    <p class=\"text-xs font-weight-bold mb-0 \">" . $row["matches_draw"] . "</p>
                                                </td>
                                                <td class=\"align-middle text-center text-sm\">
                                                    <p class=\"text-xs font-weight-bold mb-0 \">" . $row["matches_loses"] . "</p>
                                                </td>   
                                                <td class=\"align-middle text-center text-sm\">
                                                    <p class=\"text-xs font-weight-bold mb-0 \">" . $row["goals_favor"] . "</p>
                                                </td>
                                                <td class=\"align-middle text-center text-sm\">
                                                    <p class=\"text-xs font-weight-bold mb-0 \">" . $row["goals_against"] . "</p>
                                                </td>
                                                <td class=\"align-middle text-center text-sm\">
                                                    <p class=\"text-xs font-weight-bold mb-0 \">" . $row["goals_difference"] . "</p>
                                                </td>
                                                <td class=\"align-middle text-center text-sm\">
                                                    <p class=\"text-xs font-weight-bold mb-0 \">" . $row["points"] . "</p>
                                                </td>
                                            </tr>";
                        }
                        echo                        "</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                        ";
                    }
                }
                ?>


            </div>
        </div>
    </main>


</body>

</html>