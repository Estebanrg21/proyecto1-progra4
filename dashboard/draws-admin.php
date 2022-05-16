<?php
require_once(__DIR__ . "/../models/Team.php");
require_once(__DIR__ . "/../models/Edition.php");
require_once(__DIR__ . "/../util.php");
require_once(__DIR__ . "/../database/database.php");
[$db, $connection] = Database::getConnection();
if (areSubmitted(["edition"])) {
    if (checkInput(["edition"])) {
        $result = Team::draw($connection, $_POST["edition"]);
        [$text, $isOk] = Team::$responseCodes[$result];
        $infoFormMessage = $text;
        $classMessage = (($isOk) ? "success" : "danger");
    } else {
        $infoFormMessage = "Field cannot be empty";
        $classMessage = "danger";
    }
} else if (areSubmitted(["seeEdition"])) {
    if (checkInput(["seeEdition"])) {
        $result = Team::getTeamGroupsByEdition($connection, $_POST["seeEdition"]);
        if ($result) {
            $groups = $result;
        } else {
            $infoFormMessage = "Groups cannot be found";
            $classMessage = "danger";
        }
    } else {
        $infoFormMessage = "Field cannot be empty";
        $classMessage = "danger";
    }
}
?>


<?php
$option = 4;
require_once(__DIR__ . '/../templates/dashboard-top-template.php')
?>

<div class="col-12 col-xl-4">
    <div class="card h-100 bg-cdark">
        <div class="card-header pb-0 p-3 border-0 d-flex align-items-center bg-cdark">
            <h6 class="mb-0 text-white" id="mainFormTitle"><?php echo (isset($formText) ? $formText : "Generate groups") ?></h6>
            <p class="btn btn-link pe-3 ps-0 mb-0 ms-auto" id="showGroups">Show groups</p>
            <p class="btn btn-link pe-3 ps-0 mb-0 ms-auto" id="clearMainForm">Clear</p>
        </div>
        <div class="card-body p-3">
            <form action="#" method="post" style="font-family: 'Roboto', sans-serif !important;">
                <p class="text-<?php echo (isset($classMessage) ? $classMessage : " d-none") ?> text-xs font-weight-bolder mb-3" id="messageMainForm"><?php echo (isset($infoFormMessage) ? $infoFormMessage : "") ?></p>
                <div class="input-group flex-md-fill mb-3" style="z-index:0;">

                    <select class="form-select bg-cdark c-input-dark dark-select" name="edition" id="editionSelect" aria-label="Select">
                        <option selected value="">Edition</option>
                        <?php

                        $editions = Edition::getAllEditions($connection);
                        if ($editions) {
                            while ($row = $editions->fetch_array(MYSQLI_ASSOC)) {
                                echo "<option  value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" id="mainFormButton" class="btn bg-gradient-info w-40 mt-4 mb-0">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                                                <th class=\"text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-10 ps-2 \">Country</th>
                                            </tr>
                                        </thead>
                                        <tbody>";
                foreach ($teams as $row) {
                                        echo    "<tr>
                                                <td class=\"align-middle text-center text-sm\">
                                                        <p class=\"text-xs font-weight-bold mb-0 \">" . $row["team"] . "</p>
                                                </td>  
                                                <td class=\"align-middle text-center text-sm\">
                                                        <p class=\"text-xs font-weight-bold mb-0 \">" . $row["country"] . "</p>
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
<script>
    let SHOWMESSAGE = true;
    let showGroups = document.getElementById("showGroups");
    if (showGroups) {
        showGroups.addEventListener("click", () => {
            SHOWMESSAGE = false;
            let sel = document.getElementsByName("edition")[0];
            if (sel) {
                sel.name = "seeEdition";
            }
            document.getElementById('mainFormTitle').textContent = 'Show groups';
            document.getElementById('mainFormButton').textContent = 'Show';
        });
    }

    let submitButton = document.getElementById("mainFormButton");
    submitButton.addEventListener("click",(e)=>{
        e.preventDefault();
        if (SHOWMESSAGE) {
         if (confirm("WARNING!!\nDo you really want to generate groups? \n \
         Note: If there are already groups for this edition, all of the information will be overwritten, which means, every progress will be lost")) {
             e.target.form.submit();
         }   
        }
    });
</script>
<?php
$scripts = [];

$scripts[] = "
<script>
    document.getElementById('clearMainForm').addEventListener('click', (e) => {
        window.history.replaceState({}, document.title, window.location.pathname);
        document.getElementById('mainFormTitle').textContent = 'Generate groups';
        document.getElementById('mainFormButton').textContent = 'Generate';
        let formMsg = document.getElementById('messageMainForm');
        formMsg.classList.remove('text-success');
        formMsg.classList.remove('text-danger');
        formMsg.classList.add('d-none');
        sel = document.getElementById('editionSelect');
        sel.name='edition';
        sel.selectedIndex = 0;
    });
</script>
";
?>

<?php
require_once(__DIR__ . '/../templates/dashboard-bottom-template.php')
?>