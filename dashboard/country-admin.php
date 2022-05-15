<?php
require_once(__DIR__ . "/../models/Country.php");
require_once(__DIR__ . "/../util.php");
require_once(__DIR__ . "/../database/database.php");
[$db, $connection] = Database::getConnection();

if (areSubmitted(["name", "id"])) {
    if (checkInput(["name"])) {
        $country = new Country($_POST['name'], $_POST['id']);
        $country->connection = $connection;
        $result = $country->update();
        [$text, $isOk] = Country::$responseCodes[$result];
        $infoFormMessage = $text;
        $classMessage = (($isOk) ? "success" : "danger");
    } else {
        $infoFormMessage = "Fields cannot be empty";
        $classMessage = "danger";
    }
} else if (areSubmitted(["id"])) {
    $country = Country::getCountry($connection, $_POST["id"], true, false);
    if ($country) {
        $id = $country["id"];
        $name = $country["name"];
        $blockIdInput = true;
        $formText = "Update country";
        $formButtonText = "Update";
    } else {
        $infoFormMessage = "Country not found";
        $classMessage = "danger";
    }
} else if (areSubmitted(["name"])) {
    if (checkInput(["name"])) {
        $country = new Country($_POST['name']);
        $country->connection = $connection;
        $result = $country->save();
        [$text, $isOk] = Country::$responseCodes[$result];
        $infoFormMessage = $text;
        $classMessage = (($isOk) ? "success" : "danger");
    } else {
        $infoFormMessage = "Field cannot be empty";
        $classMessage = "danger";
    }
}

if (isset($_GET['id'])) {
    $result = Country::removeCountry($connection, $_GET['id']);
    [$text, $isOk] = Country::$responseCodes[$result];
    $deleteResult = $text;
    $deleteMsgClass = (($isOk) ? "success" : "danger");
}
?>


<?php
$option = 1;
require_once(__DIR__ . '/../templates/dashboard-top-template.php')
?>
<?php if (isset($deleteResult)) : ?>
    <script>
      window.history.replaceState({}, document.title, `${window.location.pathname}`);
    </script>
    <div class="modal" tabindex="-1" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-center bg-<?php echo (isset($deleteMsgClass) ? $deleteMsgClass : "") ?>">
                    <p class="text-white fw-bold  m-0 p-0" id="messageMainForm"><?php echo (isset($deleteResult) ? $deleteResult : "") ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="col-12 col-xl-4">
    <div class="card h-100 bg-cdark">
        <div class="card-header pb-0 p-3 border-0 d-flex align-items-center bg-cdark">
            <h6 class="mb-0 text-white" id="mainFormTitle"><?php echo (isset($formText) ? $formText : "Add country") ?></h6>
            <p class="btn btn-link pe-3 ps-0 mb-0 ms-auto" id="clearMainForm">Clear</p>
        </div>
        <div class="card-body p-3">
            <form role="form" method="POST" action="#" id="mainForm" style="font-family: 'Roboto', sans-serif !important;">
                <p class="text-<?php echo (isset($classMessage) ? $classMessage : " d-none") ?> text-xs font-weight-bolder mb-3" id="messageMainForm"><?php echo (isset($infoFormMessage) ? $infoFormMessage : "") ?></p>
                <?php if (isset($blockIdInput)) : ?>
                    <div class="mb-3" id="mainField">
                        <h6 class="text-uppercase text-body text-xs font-weight-bolder">Country ID</h6>
                        <div>
                            <input type="hidden" name="id" value="<?php echo (isset($id) ? $id : "")  ?>">
                            <input type="text" class="bg-cdark c-input-dark  form-control" id="formId" aria-label="id" aria-describedby="food-time-addon" value="<?php echo (isset($id) ? $id : "")  ?>" <?php echo (isset($blockIdInput) ? "disabled" : "")  ?>>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="mb-3 ">
                    <h6 class="text-uppercase  text-xs font-weight-bolder text-white">Country name</h6>
                    <div>
                        <input type="text" autocomplete="new-password" style="border-bottom-left-radius:0 !important;border-top-left-radius:0 !important;" class="bg-cdark c-input-dark form-control" id="mainFormName" placeholder="Name" name="name" aria-label="Name" aria-describedby="text-addon" value="<?php echo (isset($name) ? $name : "")  ?>">
                    </div>
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
                <h6 class="text-white">Countries</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" style="font-family: 'Roboto', sans-serif !important;">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ">ID</th>
                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2 ">Name</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $countries = Country::getAllCountries($connection);
                            if ($countries) {
                                while ($row = $countries->fetch_array(MYSQLI_ASSOC)) {
                                    echo "
                                        <tr>
                                        <td class=\"align-middle text-center text-sm\">
                                            <input type=\"hidden\" value=\"" . $row['id'] . "\" country-id />
                                            <p class=\"text-xs font-weight-bold mb-0 \">" . $row["id"] . "</p>
                                        </td>
                                        
                                        <td class=\"align-middle text-center text-sm\">
                                            <input type=\"hidden\"  value=\"" . $row['name'] . "\" food-time-name />
                                            <p class=\"text-xs font-weight-bold mb-0\">" . $row["name"] . "</p>
                                        </td>";
                                    echo "<td><div class=\"d-flex justify-content-center align-items-center\">";
                                    echo "
                                        <form action=\"#\" method=\"post\" class=\"m-0 p-0\">
                                        <input type=\"hidden\" value=\"" . $row['id'] . "\" name=\"id\" />
                                        <button type=\"submit\" class=\"btn btn-link text-muted px-3 mb-0 \" >
                                            <i class=\"fas fa-pencil-alt text-muted me-2\" aria-hidden=\"true\"></i>Update
                                        </button>
                                        </form>
                                        ";
                                    echo "
                                        <form action=\"#\" method=\"get\" class=\"m-0 p-0\">
                                        <input type=\"hidden\" value=\"" . $row['id'] . "\" name=\"id\" />
                                        <button class=\"btn btn-link text-danger px-3 mb-0 \" delete-item>
                                            <i class=\"far fa-trash-alt me-2\" aria-hidden=\"true\"></i>Delete
                                        </button>
                                        </form>
                                        ";
                                    echo "</div></td>";
                                }
                            }
                            ?>
                            <script>
                                let deleteButtons = Array.prototype.slice.call(document.querySelectorAll('button[delete-item]'));
                                if (deleteButtons) {
                                    deleteButtons.forEach((element) => {
                                        element.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            if (confirm('Do you want to delete the selected item?')) {
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
$scripts = [];

$scripts [] = "
<script>
    document.getElementById('clearMainForm').addEventListener('click', (e) => {
        window.history.replaceState({}, document.title, window.location.pathname);
        document.getElementById('mainFormTitle').textContent = 'Create country';
        let mainField = document.getElementById('mainField');
        if (mainField) mainField.remove();
        document.getElementById('mainFormName').value = '';
        document.getElementById('mainFormButton').textContent = 'Create';
        let formMsg = document.getElementById('messageMainForm');
        formMsg.classList.remove('text-success');
        formMsg.classList.remove('text-danger');
        formMsg.classList.add('d-none');
    });
</script>
";
?>
<?php if (isset($deleteResult)) {
    $scripts [] = "
    <script >
        let modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    </script>";
} ?>

<?php
require_once(__DIR__ . '/../templates/dashboard-bottom-template.php')
?>