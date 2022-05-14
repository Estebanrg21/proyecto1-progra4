<?php
require_once(__DIR__ . "/../models/Country.php");
require_once(__DIR__ . "/../util.php");
require_once(__DIR__ . "/../database/database.php");
[$db, $connection] = Database::getConnection();
?>


<?php
$option = 2;
require_once(__DIR__ . '/../templates/dashboard-top-template.php')
?>


<div class="col-12 col-xl-4">
    <div class="card h-100 bg-cdark">
        <div class="card-header pb-0 p-3 border-0 d-flex align-items-center bg-cdark">
            <h6 class="mb-0 text-white" id="formUserTitle"><?php echo (isset($formText) ? $formText : "Create team") ?></h6>
            <p class="btn btn-link pe-3 ps-0 mb-0 ms-auto" id="clearUserForm">Clear</p>
        </div>
        <div class="card-body p-3">
            <form role="form" method="POST" action="#" id="mainForm" style="font-family: 'Roboto', sans-serif !important;">
                <?php if (isset($errorSubmission)) : ?>
                    <p class="text-danger text-xs font-weight-bolder mb-3" id="errorMessageMainForm"><?php echo $errorSubmission; ?></p>
                <?php endif; ?>

                <div class="mb-3 ">
                    <h6 class="text-uppercase  text-xs font-weight-bolder text-white">Team name</h6>
                    <div>
                        <input type="text" autocomplete="new-password" style="border-bottom-left-radius:0 !important;border-top-left-radius:0 !important;" class="bg-cdark c-input-dark form-control" id="mainFormName" placeholder="Nombre" name="name" aria-label="Nombre" aria-describedby="text-addon" value="<?php echo (isset($name) ? $name : "")  ?>">
                    </div>
                </div>
                <div class="input-group flex-md-fill mb-3" style="z-index:0;">
                    <select class="form-select bg-cdark c-input-dark dark-select" name="fId" id="validateSelect" aria-label="Select">
                        <option selected value="">Country</option>
                        <?php

                        $countries = Country::getAllCountries($connection);
                        if ($countries) {
                            while ($row = $countries->fetch_array(MYSQLI_ASSOC)) {
                                echo "<option  value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
                            }
                        }

                        ?>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" id="mainFormButton" class="btn bg-gradient-info w-40 mt-4 mb-0"><?php echo (isset($formButtonText) ? $formButtonText : "Create") ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card mb-4 bg-cdark">
            <div class="card-header pb-0 bg-cdark">
                <h6 class="text-white">Teams</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" style="font-family: 'Roboto', sans-serif !important;">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ">Identificador</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2 ">Nombre</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /*
                            $foodTimes = FoodTime::getAllFoodTimes($connection);
                            if ($foodTimes) {
                                while ($row = $foodTimes->fetch_array(MYSQLI_ASSOC)) {
                                    echo "
                            <tr>
                              <td class=\"align-middle text-center text-sm\">
                                <input type=\"hidden\" value=\"" . $row['id'] . "\" food-time-id />
                                <p class=\"text-xs font-weight-bold mb-0 \">" . $row["id"] . "</p>
                              </td>
                              
                              <td class=\"align-middle text-center text-sm\">
                                <input type=\"hidden\"  value=\"" . $row['name'] . "\" food-time-name />
                                <p class=\"text-xs font-weight-bold mb-0\">" . $row["name"] . "</p>
                              </td>

                              <td class=\"align-middle text-center text-sm text-wrap\">
                                <input type=\"hidden\"  value=\"" . $row['description'] . "\" food-time-description />
                                <p class=\"text-xs font-weight-bold mb-0 text-wrap\">" . $row["description"] . "</p>
                              </td>";
                                    echo "<td><div class=\"d-flex justify-content-center align-items-center\">";
                                    echo "
                                <form action=\"#\" method=\"get\" class=\"m-0 p-0\">
                                  <input type=\"hidden\" value=\"" . $row['id'] . "\" name=\"id\" />
                                  <input type=\"hidden\" value=\"u\" name=\"m\" />
                                  <button type=\"submit\" class=\"btn btn-link text-dark px-3 mb-0 \" >
                                    <i class=\"fas fa-pencil-alt text-dark me-2\" aria-hidden=\"true\"></i>Actualizar
                                  </button>
                                </form>
                                ";
                                    echo "
                                <form action=\"#\" method=\"get\" class=\"m-0 p-0\">
                                  <input type=\"hidden\" value=\"" . $row['id'] . "\" name=\"id\" />
                                  <input type=\"hidden\" value=\"d\" name=\"m\" />
                                  <button class=\"btn btn-link text-danger px-3 mb-0 \" delete-item>
                                    <i class=\"far fa-trash-alt me-2\" aria-hidden=\"true\"></i>Eliminar
                                  </button>
                                </form>
                                ";
                                    echo "</div></td>";
                                }
                            }*/
                            ?>
                            <script>
                                let deleteButtons = Array.prototype.slice.call(document.querySelectorAll('button[delete-item]'));
                                if (deleteButtons) {
                                    deleteButtons.forEach((element) => {
                                        element.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            if (confirm('¿Desea eliminar el tiempo de comida?')) {
                                                e.target.form.submit();
                                            }
                                        })
                                    });
                                }
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once(__DIR__ . '/../templates/dashboard-bottom-template.php')
?>