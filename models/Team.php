<?php
require_once(__DIR__ . '/../database/database.php');

class Team
{

    public static $MAX_TEAMS_ALLOWED = 32;
    public static $MAX__TEAM_COUNTRY_ALLOWED = 4;

    public static $responseCodes = [
        0 => ["Team added successfully", true],
        1 => ["Team deleted successfully", true],
        2 => ["Team updated successfully", true],
        10 => ["Data cannot be empty", false],
        12 => ["There is an internal error", false],
        13 => ["The team already exists", false],
        14 =>["Teams number limit reached",false],
        15 =>["Teams per country number limit reached",false]
    ];

    function __construct($name, $country, $id = null)
    {
        $this->name = $name;
        $this->country = $country;
        $this->id = $id;
        $this->connection = null;
    }

    public static function getTeam($connection, $id = null, $name = null, $country = null, $onlyCheckExistance = true)
    {
        if ((empty($id) && empty($name) && empty($country))) {
            throw new Exception("Team fields are empty");
        } else if (empty($id)) {
            if (empty($name) || empty($country)) throw new Exception("Team fields are empty");
            $query = "SELECT " . (($onlyCheckExistance) ? "id" : "*") . " FROM teams WHERE name=? AND country_id=?";
            $fieldMap = "si";
            $fields = [$name, $country];
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
        $teamCount = Team::getTeamsCount($this->connection);
        if($teamCount==null) return $response;
        if($teamCount==Team::$MAX_TEAMS_ALLOWED)return 14;
        $countryCount = Team::getCountryCount($this->connection,$this->country);
        if($countryCount==null) return $response;
        if($countryCount==Team::$MAX__TEAM_COUNTRY_ALLOWED)return 15;
        if (empty($this->name)) return 10;
        
        if (Team::getTeam($this->connection, null, $this->name, $this->country)) return 13;
        else {
            $statement = $this->connection->prepare("INSERT INTO teams(name,country_id) VALUES (?, ?)");
            $statement->bind_param('si', $this->name, $this->country);
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
        if (empty($this->name) || empty($this->country)) return 10;
        if (Team::getTeam($this->connection,$this->id)) {
            $statement = $this->connection->prepare("UPDATE  teams SET name=?, country_id=? WHERE id=?");
            $statement->bind_param('sii', $this->name, $this->country, $this->id);
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

    public static function getTeamsCount($connection){
        $result = $connection->query("SELECT count(id) as id from teams");
        if ($result) {
            $arr=$result->fetch_array(MYSQLI_ASSOC);
            return  $arr["id"];
        }
        return null;
    }

    public static function getCountryCount($connection,$countryId){
        $statement = $connection->prepare("SELECT count(country_id) as country from teams where country_id=?");
        $statement->bind_param('i', $countryId);
        $statement->execute();
        if ($statement) {
            $result = $statement->get_result();
            $arr=$result->fetch_array(MYSQLI_ASSOC);
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
}
