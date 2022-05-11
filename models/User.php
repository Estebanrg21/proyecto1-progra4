<?php
require_once(__DIR__.'/../database/database.php');

class User{

    function __construct($username,$password=null){
        $this->username =$username;
        $this->password = $password;
    }
    function encryptPassword(){
        $this->password = md5($this->password);
    }
    function login(){
        $this->encryptPassword();
        $query="SELECT * FROM users WHERE username='$this->username' AND password='$this->password'";
        $result = $this->connection->query($query);
        $this->connection->close();
        return $result->num_rows>=1;
    }

}