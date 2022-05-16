<?php
require_once(__DIR__ . "/../models/Edition.php");
require_once(__DIR__ . "/../models/Team.php");
require_once(__DIR__ . "/../models/MatchTeams.php");
require_once(__DIR__ . "/../util.php");
require_once(__DIR__ . "/../database/database.php");
[$db, $connection] = Database::getConnection();
if (areSubmitted(["edition", "group"])) {
    $matches = MatchTeams::getAllMatches($connection, $_POST["edition"], $_POST["group"]);
    if (!$matches) {
        $deleteResult = "Matches could not be found";
        $deleteMsgClass = (($isOk) ? "success" : "danger");
        unset($matches);
    }
} else if (areSubmitted(["edition"])) {
    $groups = Team::getGroupsByEdition($connection, $_POST["edition"]);
    if ($groups) {
        $edition = $_POST["edition"];
        $formText = "Search matches";
        $formButtonText = "Search ";
    } else {
        $infoFormMessage = "Edition not found";
        $classMessage = "danger";
    }
}else if(areSubmitted(["id","goalsLocal","goalsVisit"])){
    if(checkIfAreNumeric([$_POST["goalsLocal"],$_POST["goalsVisit"]])){
        $matchResult  = new MatchTeams($_POST["id"],null,null,null,null,$_POST["goalsLocal"],$_POST["goalsVisit"]);
        $matchResult->connection=$connection;
        $result = $matchResult->update();
        [$text, $isOk] = MatchTeams::$responseCodes[$result];
        if(!$isOk){
            $matchFields=[
                "goalsLocal"=>$_POST["goalsLocal"],
                "goalsVisit"=>$_POST["goalsVisit"],
                "local_name"=>$_POST["local"],
                "visit_name"=>$_POST["visit"],
                "id"=>$_POST["id"]
            ];
            $formText = "Set match result";
            $formButtonText = "Set result";
        }
        $infoFormMessage = $text;
        $classMessage = (($isOk) ? "success" : "danger");
    }else{
        $matchFields=[
            "goalsLocal"=>$_POST["goalsLocal"],
            "goalsVisit"=>$_POST["goalsVisit"],
            "local_name"=>$_POST["local"],
            "visit_name"=>$_POST["visit"],
            "id"=>$_POST["id"]
        ];
        $formText = "Set match result";
        $formButtonText = "Set result";
        $infoFormMessage = "Fields are not numeric";
        $classMessage = "danger";
    }
} else if (areSubmitted(["id"])) {
    $match = MatchTeams::getMatch($connection, $_POST["id"], false);
    if (!$match) {
        $infoFormMessage = "Match not found";
        $classMessage = "danger";
        unset($match);
    } else {
        $formText = "Set match result";
        $formButtonText = "Set result";
    }
} 
?>


<?php
$option = 6;
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
<?php if (!isset($matches)) : ?>
<div class="col-12 col-xl-4">
    <div class="card h-100 bg-cdark">
        <div class="card-header pb-0 p-3 border-0 d-flex align-items-center bg-cdark">
            <h6 class="mb-0 text-white" id="mainFormTitle"><?php echo (isset($formText) ? $formText : "Search groups") ?></h6>
        </div>
        
        <div class="card-body p-3">
            <form role="form" method="POST" action="#" id="mainForm" style="font-family: 'Roboto', sans-serif !important;">
            <p class="text-<?php echo (isset($classMessage) ? $classMessage : " d-none") ?> text-xs font-weight-bolder mb-3" id="messageMainForm"><?php echo (isset($infoFormMessage) ? $infoFormMessage : "") ?></p>
                <?php if ((!isset($edition) && !isset($groups) && !isset($matches)) && !isset($match) && !isset($matchFields)) : ?>
                    <h6 class="text-uppercase  text-xs font-weight-bolder text-white">Edition</h6>
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
                <?php endif; ?>
                <?php if (isset($edition) && isset($groups) && !isset($match) & !isset($matchFields)) : ?>
                    <h6 class="text-uppercase  text-xs font-weight-bolder text-white">Group</h6>
                    <div class="input-group flex-md-fill mb-3" style="z-index:0;">
                        <input type="hidden" name="edition" value="<?php echo $edition; ?>">
                        <select class="form-select bg-cdark c-input-dark dark-select" name="group" id="groupSelect" aria-label="Select">
                            <option selected value="">Group</option>
                            <?php
                            if ($groups) {
                                while ($row = $groups->fetch_array(MYSQLI_ASSOC)) {
                                    echo "<option  value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if (isset($match) || isset($matchFields)) : ?>
                    <input type="hidden" name="id" value="<?php echo ((isset($match))?$match["id"]:$matchFields["id"])?>">
                    <input type="hidden" name="local" value="<?php echo ((isset($match))?$match["local_name"]:$matchFields["local_name"])?>">
                    <input type="hidden" name="visit" value="<?php echo ((isset($match))?$match["visit_name"]:$matchFields["visit_name"])?>">
                    <div
                     class="mb-3 ">
                        <h6 class="text-uppercase  text-xs font-weight-bolder text-white"><?php echo ((isset($match))?$match["local_name"]:$matchFields["local_name"])?></h6>
                        <div>
                            <input type="text" autocomplete="new-password" style="border-bottom-left-radius:0 !important;border-top-left-radius:0 !important;" class="bg-cdark c-input-dark form-control" placeholder="Goals" name="goalsLocal" aria-label="Goals" aria-describedby="text-addon" value="<?php echo ((isset($matchFields))?$matchFields["goalsLocal"]:"")?>">
                        </div>
                    </div>
                    <div class="mb-3 ">
                            <h6 class="text-uppercase  text-xs font-weight-bolder text-white"><?php echo ((isset($match))?$match["visit_name"]:$matchFields["visit_name"])?></h6>
                            <div>
                                <input type="text" autocomplete="new-password" style="border-bottom-left-radius:0 !important;border-top-left-radius:0 !important;" class="bg-cdark c-input-dark form-control" placeholder="Goals" name="goalsVisit" aria-label="Goals" aria-describedby="text-addon" value="<?php echo ((isset($matchFields))?$matchFields["goalsVisit"]:"")?>">
                            </div>
                        </div>
                <?php endif; ?>
                <div class="text-center">
                    <button type="submit" id="mainFormButton" class="btn bg-gradient-info w-40 mt-4 mb-0"><?php echo (isset($formButtonText) ? $formButtonText : "Search") ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if (isset($matches)) : ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4 bg-cdark">
                <div class="card-header pb-0 bg-cdark">
                    <h6 class="text-white">Matches</h6>
                </div>
                <div class="card-body  p-3">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" style="font-family: 'Roboto', sans-serif !important;">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2 ">Local team</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2 ">Visit team</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2 ">Result</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($matches) {
                                    while ($row = $matches->fetch_array(MYSQLI_ASSOC)) {
                                        echo "
                                        <tr>
                                        
                                        <td class=\"align-middle text-center text-sm\">
                                            <p class=\"text-xs font-weight-bold mb-0\">" . $row["local_name"] . "</p>
                                        </td>
                                        
                                        <td class=\"align-middle text-center text-sm\">
                                            <p class=\"text-xs font-weight-bold mb-0\">" . $row["visit_name"] . "</p>
                                        </td>

                                        <td class=\"align-middle text-center text-sm\">
                                            <p class=\"text-xs font-weight-bold mb-0\">" . $row["result"] . "</p>
                                        </td>
                                        ";
                                        echo "<td><div class=\"d-flex justify-content-center align-items-center\">";
                                        echo "
                                        <form action=\"#\" method=\"post\" class=\"m-0 p-0\">
                                        <input type=\"hidden\" value=\"" . $row['id'] . "\" name=\"id\" />
                                        <button type=\"submit\" class=\"btn btn-link text-muted px-3 mb-0 \" >
                                            <i class=\"fas fa-pencil-alt text-muted me-2\" aria-hidden=\"true\"></i>Update
                                        </button>
                                        </form>
                                        ";
                                        echo "</div></td>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php

?>
<?php if (isset($deleteResult)) {
    $scripts[] = "
    <script >
        let modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    </script>";
} ?>

<?php
require_once(__DIR__ . '/../templates/dashboard-bottom-template.php')
?>