<?php
require_once(__DIR__.'/../database/database.php');

class Edition{

    public static $responseCodes = [
        0=>["Edition added successfully",true],
        1=>["Edition deleted successfully",true],
        2=>["Edition updated successfully",true],
        10=>["Data cannot be empty",false],
        11=>["Update cannot be done because item does not exists",false],
        12=>["There is an internal error",false],
        13=>["Name length is greater than expected",false],
    ];

    private static $FIELDS_LENGTH= [
        "name"=>100
    ];

    function __construct($name,$id=null){
        $this->name =$name;
        $this->id=$id;
        $this->connection=null;
    }

    public static function getEdtion($connection,$id,$onlyCheckExistance=true){
        $statement = $connection->prepare("SELECT ".(($onlyCheckExistance)?"id":"*")." FROM editions WHERE id=?");
        $statement->bind_param("i",$id);
        $statement->execute();
        if($statement)
            $result = $statement->get_result();
            return (($onlyCheckExistance)?$result->num_rows>=1:$result->fetch_array(MYSQLI_ASSOC));
        return null;
    }

    function save(){
        $response = 12;
        if(empty($this->name)) return 10;
        if(strlen($this->name)>Edition::$FIELDS_LENGTH["name"]) return 13;
        $statement = $this->connection->prepare("INSERT INTO editions(name) VALUES (?)");
        $statement->bind_param('s',$this->name);
        $statement->execute();
        $response = 0;
        if(!$statement)
            $response = 12; 
        return $response;
    }

    function update(){
        $response = 12;
        if(empty($this->name)) return 10;
        if(Edition::getEdtion($this->connection,$this->id)){
            $statement = $this->connection->prepare("UPDATE  editions SET name=? WHERE id=?");
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
    
    public static function removeEdition($connection,$id){
        $response=12;
        $statement = $connection->prepare("DELETE FROM editions WHERE id=?");
        $statement->bind_param('i',$id);
        if($statement){
            $statement->execute();
            if($statement) $response=1;
        }
        return $response;
    }

    public static function getAllEditions($connection){
        $result = $connection->query("SELECT * FROM editions");
        return $result;
    } 

}   