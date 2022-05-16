<?php
require_once(__DIR__ . '/../database/database.php');

class MatchTeams
{

    public static $responseCodes = [
        2 => ["Match updated successfully", true],
        10 => ["Data cannot be empty", false],
        11 => ["Update cannot be done because item does not exists", false],
        12 => ["There is an internal error", false],
    ];


    function __construct($id, $localTeam=null, $visitTeam=null, $groupId=null, $editionId=null,$goalsLocal=null,$goalsVisit=null)
    {
        $this->id = $id;
        $this->localTeam = $localTeam;
        $this->visitTeam = $visitTeam;
        $this->groupId = $groupId;
        $this->editionId = $editionId;
        $this->goalsLocal=$goalsLocal;
        $this->goalsVisit = $goalsVisit;
        $this->connection = null;
    }

    public static function getMatch($connection, $id, $onlyCheckExistance = true)
    {
        if($onlyCheckExistance){
            $query = "SELECT id FROM matches WHERE id=?";
        }else{
            $query = "select matches.id as id, t1.id as local, t2.id as visit,t1.name
             as local_name,t2.name as visit_name, group_id, matches.edition_id, result from matches 
            JOIN teams AS t1  ON local_team = t1.id
            JOIN teams AS t2  ON visit_team = t2.id
            where matches.id=?";
        }
        $statement = $connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        if ($statement)
            $result = $statement->get_result();
        return (($onlyCheckExistance) ? $result->num_rows >= 1 : $result->fetch_array(MYSQLI_ASSOC));
        return null;
    }

    function update()
    {
        $response = 12;
        if (empty($this->id) || empty($this->goalsLocal) || empty($this->goalsVisit)) return 10;
        $existant = MatchTeams::getMatch($this->connection, $this->id,false);
        if ($existant) {
            $this->localTeam = $existant["local"];
            $this->visitTeam = $existant["visit"];
            $statement = $this->connection->prepare("DELETE FROM match_details WHERE match_id=?");
            $statement->bind_param('i', $this->id);
            $statement->execute();
            if ($statement) {
                $matchDetails=[
                [
                    $this->localTeam,
                    $this->id,
                    $this->goalsLocal,
                    $this->goalsVisit,
                    (($this->goalsLocal>$this->goalsVisit)?"WIN":($this->goalsLocal<$this->goalsVisit?"LOSE":"DRAW"))
                ],
                [
                    $this->visitTeam,
                    $this->id,
                    $this->goalsVisit,
                    $this->goalsLocal,
                    (($this->goalsVisit>$this->goalsLocal)?"WIN":($this->goalsVisit<$this->goalsLocal?"LOSE":"DRAW"))
                ]];
                foreach ($matchDetails as &$detail) {
                    $statement = $this->connection->prepare("INSERT INTO match_details(team_id,match_id,goals_favor,goals_against,result) VALUES (?,?,?,?,?) ");
                    $statement->bind_param('iiiis',...$detail);
                    $statement->execute();
                    if (!$statement)
                        return 12;
                }
                $matchRes=$this->goalsLocal."-".$this->goalsVisit;
                $statement = $this->connection->prepare("UPDATE  matches SET result=? WHERE id=?");
                $statement->bind_param('si',$matchRes, $this->id);
                $statement->execute();
                if ($statement)
                    return 2;
            }
            return $response = 12;
        } else {
            return 11;
        }
        if (isset($statement)) {
            $statement->execute();
            if (!$statement) $response = 12;
        }
        return $response;
    }

    public static function getAllMatches($connection, $editionId, $groupId)
    {
        $statement = $connection->prepare("select matches.id as id, t1.id as local, t2.id as visit,t1.name
        as local_name,t2.name as visit_name, group_id, matches.edition_id, result from matches 
       JOIN teams AS t1  ON local_team = t1.id
       JOIN teams AS t2  ON visit_team = t2.id
        where group_id=? and matches.edition_id=?");
        $statement->bind_param('ii', $groupId, $editionId);
        $statement->execute();
        if ($statement)
            return $statement->get_result();
        return null;
    }
}
