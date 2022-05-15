<?php
require_once(__DIR__.'/../database/database.php');

class Country{

    public static $responseCodes = [
        0=>["Country added successfully",true],
        1=>["Country deleted successfully",true],
        2=>["Country updated successfully",true],
        10=>["Data cannot be empty",false],
        11=>["Update cannot be done because item does not exists",false],
        12=>["There is an internal error",false],
        13=>["The country already exists",false],
    ];

    function __construct($name,$id=null){
        $this->name =$name;
        $this->id=$id;
        $this->connection=null;
    }

    public static function getCountry($connection,$key,$isId=false,$onlyCheckExistance=true){
        $kMap = "s";
        $kColumn = "name";
        if ($isId) {
            $kMap="i";
            $kColumn="id";
        }
        $statement = $connection->prepare("SELECT ".(($onlyCheckExistance)?"id":"*")." FROM countries WHERE ".$kColumn."=?");
        $statement->bind_param($kMap,$key);
        $statement->execute();
        if($statement)
            $result = $statement->get_result();
            return (($onlyCheckExistance)?$result->num_rows>=1:$result->fetch_array(MYSQLI_ASSOC));
        return null;
    }

    function save(){
        $response = 12;
        if(empty($this->name)) return 10;
        if(Country::getCountry($this->connection,$this->name,false)) return 13;
        else{
            $statement = $this->connection->prepare("INSERT INTO countries(name) VALUES (?)");
            $statement->bind_param('s',$this->name);
            $response = 0;
        }
        if(isset($statement)){
            $statement->execute();
            if(!$statement) $response = 12;
        }    
        return $response;
    }

    function update(){
        $response = 12;
        if(empty($this->name)) return 10;
        if(Country::getCountry($this->connection,$this->id,true)){
            $statement = $this->connection->prepare("UPDATE  countries SET name=? WHERE id=?");
            $statement->bind_param('si',$this->name,$this->id);
            $response = 2;   
        }else{ 
            return 11;
        }
        if(isset($statement)){
            $statement->execute();
            if(!$statement) $response = 12;
        }    
        return $response;
    }
    
    public static function removeCountry($connection,$id){
        $response=12;
        $statement = $connection->prepare("DELETE FROM countries WHERE id=?");
        $statement->bind_param('i',$id);
        if(isset($statement)){
            $statement->execute();
            if($statement) $response=1;
        }
        return $response;
    }

    public static function getAllCountries($connection){
        $result = $connection->query("SELECT * FROM countries");
        return $result;
    } 

}   