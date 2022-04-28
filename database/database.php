<?php
 /*
 connect with ssl 
 https://stackoverflow.com/questions/9738712/connect-to-remote-mysql-server-with-ssl-from-php
 */


$host_db = "192.168.100.98";
$user_db = "proyectoAdmin";
$passwd_db = "root123";
$db_name = "proyecto1";
$tbl_name = "";

class Database
{
    function __construct($h = null, $u = null, $p = null, $db = null)
    {
        global $host_db, $user_db, $passwd_db, $db_name;

        $this->host = $h ?? $host_db;
        $this->user_db = $u ?? $user_db;
        $this->user_pass_db = $p ?? $passwd_db;
        $this->db = $db ?? $db_name;
    }

    function connect()
    {
        return (new mysqli(
            $this->host,
            $this->user_db,
            $this->user_pass_db,
            $this->db
            ));
    }
}
