<?php
require_once(__DIR__ . '/../database/database.php');

class Team
{

    public static $MAX_TEAMS_ALLOWED = 32;
    public static $MAX_TEAM_COUNTRY_ALLOWED = 4;
    public static $MAX_TEAMS_GROUPS_ALLOWED = 8;
    public static $MAX_TEAMS_GROUP_ALLOWED = 4;

    public static $responseCodes = [
        0 => ["Team added successfully", true],
        1 => ["Team deleted successfully", true],
        2 => ["Team updated successfully", true],
        3 => ["Groups created successfully", true],
        10 => ["Data cannot be empty", false],
        12 => ["There is an internal error", false],
        13 => ["The team already exists", false],
        14 => ["Teams number limit reached", false],
        15 => ["Teams per country number limit reached", false],
        16 => ["Name length is greater than expected", false],
        17 => ["Teams quantity does not fit the required", false],

    ];

    private static $FIELDS_LENGTH = [
        "name" => 100
    ];

    function __construct($name, $country, $edition, $id = null)
    {
        $this->name = $name;
        $this->country = $country;
        $this->country = $country;
        $this->edition = $edition;
        $this->id = $id;
        $this->connection = null;
    }

    public static function getTeam($connection, $id = null, $name = null, $country = null, $edition = null, $onlyCheckExistance = true)
    {
        if ((empty($id) && empty($name) && empty($country) && empty($edition))) {
            throw new Exception("Team fields are empty");
        } else if (empty($id)) {
            if (empty($name) || empty($country) || empty($edition)) throw new Exception("Team fields are empty");
            $query = "SELECT " . (($onlyCheckExistance) ? "id" : "*") . " FROM teams WHERE name=? AND country_id=? AND edition_id=?";
            $fieldMap = "sii";
            $fields = [$name, $country, $edition];
        } else {
            $query = "SELECT " . (($onlyCheckExistance) ? "id" : "*") . " FROM teams WHERE id=?";
            $fieldMap = "i";
            $fields = [$id];
        }
        $statement = $connection->prepare($query);
        $statement->bind_param($fieldMap, ...$fields);
        $statement->execute();
        if ($statement)
            $result = $statement->get_result();
        return (($onlyCheckExistance) ? $result->num_rows >= 1 : $result->fetch_array(MYSQLI_ASSOC));
        return null;
    }

    function save()
    {
        $response = 12;
        if (empty($this->name) || empty($this->country)  || empty($this->edition)) return 10;
        if (strlen($this->name) > Team::$FIELDS_LENGTH["name"]) return 16;
        $teamCount = Team::getTeamsCount($this->connection, $this->edition);
        if ($teamCount == null && $teamCount != 0) return $response;
        if ($teamCount == Team::$MAX_TEAMS_ALLOWED) return 14;
        $countryCount = Team::getCountryCount($this->connection, $this->country, $this->edition);
        if ($countryCount == null && $teamCount != 0) return $response;
        if ($countryCount == Team::$MAX_TEAM_COUNTRY_ALLOWED) return 15;
        if (Team::getTeam($this->connection, null, $this->name, $this->country, $this->edition)) return 13;
        else {
            $statement = $this->connection->prepare("INSERT INTO teams(name,country_id,edition_id) VALUES (?, ?, ?)");
            $statement->bind_param('sii', $this->name, $this->country, $this->edition);
            $response = 0;
        }
        if (isset($statement)) {
            $statement->execute();
            if (!$statement) $response = 12;
        }
        return $response;
    }

    function update()
    {
        $response = 12;
        if (empty($this->name) || empty($this->country) || empty($this->edition)) return 10;
        if (Team::getTeam($this->connection, $this->id)) {
            $statement = $this->connection->prepare("UPDATE  teams SET name=?, country_id=?,edition_id=? WHERE id=?");
            $statement->bind_param('siii', $this->name, $this->country, $this->edition, $this->id);
            $response = 2;
        } else {
            return 11;
        }
        if (isset($statement)) {
            $statement->execute();
            if (!$statement) $response = 12;
        }
        return $response;
    }

    public static function removeTeam($connection, $id)
    {
        $response = 12;
        $statement = $connection->prepare("DELETE FROM teams WHERE id=?");
        $statement->bind_param('i', $id);
        if ($statement) {
            $statement->execute();
            if ($statement) $response = 1;
        }
        return $response;
    }

    public static function getTeamsCount($connection, $edition)
    {
        $statement = $connection->prepare("SELECT count(id) as id from teams WHERE edition_id=?");
        $statement->bind_param('i', $edition);
        $statement->execute();
        if ($statement) {
            $result = $statement->get_result();
            $arr = $result->fetch_array(MYSQLI_ASSOC);
            return  $arr["id"];
        }
        return null;
    }

    public static function getCountryCount($connection, $countryId, $editionId)
    {
        $statement = $connection->prepare("SELECT count(country_id) as country from teams where country_id=? AND edition_id=?");
        $statement->bind_param('ii', $countryId, $editionId);
        $statement->execute();
        if ($statement) {
            $result = $statement->get_result();
            $arr = $result->fetch_array(MYSQLI_ASSOC);
            return  $arr["country"];
        }
        return null;
    }

    public static function getAllTeams($connection)
    {
        $result = $connection->query("
        SELECT teams.id,teams.name as name, countries.name as country 
        FROM teams JOIN countries on country_id=countries.id
        ");
        return $result;
    }

    public static function getAllTeamsByEdition($connection, $editionId, $onlyId = false)
    {
        if ($onlyId) {
            $query = "SELECT id FROM teams WHERE edition_id=?";
        } else {
            $query = "
            SELECT teams.id,teams.name as name, countries.name as country 
            FROM teams JOIN countries on country_id=countries.id WHERE edition_id=?
            ";
        }
        $statement = $connection->prepare($query);
        $statement->bind_param('i', $editionId);
        $statement->execute();
        if ($statement)
            return $statement->get_result();
        return null;
    }

    public static function draw($connection, $editionId)
    {
        if (empty($editionId)) return 10;
        if (!(Edition::getEdtion($connection, $editionId))) return 12;
        $statement = $connection->prepare("DELETE FROM tgroups WHERE edition_id=?");
        $statement->bind_param('i', $editionId);
        $statement->execute();
        if ($statement) {
            if (Team::getTeamsCount($connection, $editionId) != Team::$MAX_TEAMS_ALLOWED) return 17;
            $groupsNames = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            $groups = [];
            foreach ($groupsNames as $name) {
                $stmt = $connection->prepare("INSERT INTO tgroups (name, edition_id) VALUES (?, ?)");
                $stmt->bind_param('si', $name, $editionId);
                $stmt->execute();
                if ($stmt)
                    $groups += [$stmt->insert_id => []];
            }
            if (count($groups) < Team::$MAX_TEAMS_GROUPS_ALLOWED) return 12;
            $teams = Team::getAllTeamsByEdition($connection, $editionId, true);
            if (!$teams) return 12;
            $teams = $teams->fetch_all(MYSQLI_ASSOC);
            shuffle($teams);
            foreach ($groups as &$group) {
                while (count($group) < Team::$MAX_TEAMS_GROUP_ALLOWED) {
                    $group[] = end($teams)["id"];
                    unset($teams[array_key_last($teams)]);
                }
            }

            foreach ($groups as $group => $teams) {
                if (is_iterable($teams)) {
                    foreach ($teams as &$team) {
                        $stmt = $connection->prepare("INSERT INTO group_teams(team_id, group_id, edition_id) VALUES (?,?,?)");
                        $stmt->bind_param('iii', $team, $group, $editionId);
                        $stmt->execute();
                        if (!$stmt)
                            return 12;
                        foreach ($teams as &$team2) {
                            if($team2!=$team){
                                $stmt = $connection->prepare("INSERT INTO matches(local_team,visit_team, group_id, edition_id) VALUES (?,?,?,?)");
                                $stmt->bind_param('iiii',$team,$team2, $group, $editionId);
                                $stmt->execute();
                                if (!$stmt)
                                    return 12;
                            }
                        }
                    }
                }
            }

            return 3;
        }
    }
    public static function getGroupsByEdition($connection, $editionId){
        $statement = $connection->prepare("select * from tgroups where edition_id=?");
        $statement->bind_param('i', $editionId);
        $statement->execute();
        if ($statement)
            return $statement->get_result();
        return null;
    }
    public static function getTeamGroupsByEdition($connection,$editionId){
        $statement = $connection->prepare("select teams.name as team, countries.name as country, 
        tgroups.name as tgroup from group_teams join teams on group_teams.team_id=teams.id 
        join countries on teams.country_id=countries.id 
        join tgroups on group_teams.group_id=tgroups.id where group_teams.edition_id=?");
        $statement->bind_param('i', $editionId);
        $statement->execute();
        if ($statement){
            $result=$statement->get_result();
            $result = $result->fetch_all(MYSQLI_ASSOC);
            $groups = array_unique(array_column($result,"tgroup","tgroup"));
            $groups=array_fill_keys($groups, []);
            foreach ($result as $row) {
                if (isset($groups[$row["tgroup"]])) {
                    $groups[$row["tgroup"]][]=[
                        "team"=>$row["team"],
                        "country"=>$row["country"]
                    ];  
                }
            }
            
            return $groups;

        }
        return null;
    }
    public static function getGroupsStatus($connection){
        $result = $connection->query("select id from editions order by id desc limit 1");
        if($result->num_rows >= 1){
            $edition = $result->fetch_array(MYSQLI_ASSOC)["id"];
            $result=$connection->query("
            select tgroups.name as tgroup, teams.name, count(match_details.id) as mp,
            count(IF(result = 'WIN', 1, NULL)) as matches_win,
            count(IF(result = 'DRAW', 1, NULL)) as matches_draw,
            count(IF(result = 'LOSE', 1, NULL)) as matches_loses,
            sum(goals_favor) as goals_favor,sum(goals_against) as goals_against,
            sum(goals_favor) - sum(goals_against) as goals_difference,
            count(IF(result = 'WIN', 1, NULL))*3  + count(IF(result = 'DRAW', 1, NULL))*1 as points
            from group_teams 
            left join match_details on match_details.team_id=group_teams.team_id
            inner join tgroups on group_id=tgroups.id
            inner join teams on teams.id=group_teams.team_id
            where group_teams.edition_id=$edition
            group by group_teams.team_id ORDER BY tgroup ASC ,points desc;");
            if($result){
                $result = $result->fetch_all(MYSQLI_ASSOC);
                $groups = [];
                foreach ($result as $row) {
                    $group = $row["tgroup"];
                    unset($row["tgroup"]);
                    if (!isset($groups[$group])) $groups[$group] = [];
                        $groups[$group][]=$row;
                }
                return $groups;
            }
        }
        return null;
    }
}
