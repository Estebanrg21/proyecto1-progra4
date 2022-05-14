<?php
require_once(__DIR__.'/../database/database.php');

class Team{

    public static $responseCodes = [
        0=>["Team added successfully",true],
        1=>["Team deleted successfully",true],
        3=>["Data cannot be empty",false],
        5=>["There is an internal error",false],
        6=>["The team already exists",false]
    ];

    function __construct($name,$country){
        $this->name =$name;
        $this->country = $country;
        $this->connection=null;
    }

    public static function getTeam($connection,$name,$country,$onlyCheckExistance=true){
        $statement = $connection->prepare("SELECT ".(($onlyCheckExistance)?"id":"*")." FROM teams WHERE name=? AND country_id=?");
        $statement->bind_param('si',$name,$country);
        $statement->execute();
        if($statement)
            $result = $statement->get_result();
            return (($onlyCheckExistance)?$result->num_rows>=1:$result->fetch_array(MYSQLI_ASSOC));
        return null;
    }

    function save(){
        $response = 5;
        if(empty($this->name)) return 3;
        if(Team::getTeam($this->connection,$this->name,$this->country)) return 6;
        else{
            $statement = $this->connection->prepare("INSERT INTO teams VALUES (?, ?)");
            $statement->bind_param('si',$this->name,$this->country);
            $response = 0;
        }
        if(isset($statement)){
            $statement->execute();
            if(!$statement) $response = 5;
        }    
        return $response;
    }
    
    public static function removeTeam($connection,$id){
        $respose=5;
        $statement = $connection->prepare("DELETE FROM teams WHERE id=?");
        $statement->bind_param('i',$id);
        if(isset($statement)){
            $statement->execute();
            if($statement) $response=1;
        }
        return $response;
    }

    public static function getAllTeams($connection){
        $result = $connection->query("
        SELECT teams.id,teams.name as name, country_id, country.name as country 
        FROM teams JOIN countries on country_id=countries.id
        ");
        return $result;
    } 

}   