<?php
namespace iutnc\backOfficeTouiter\connection;

class ConnectionFactory{
    public static array $config; 
    public static \PDO $connexion;

    public static function setConfig($file){
        if(isset(self::$config)===false){
            self::$config=parse_ini_file($file);
        }
    }

    public static function makeConnection(){
        if(isset(self::$connexion)===false){
            self::$connexion=new \PDO(self::$config["driver"].":host=".self::$config["hostname"].";dbname=".self::$config["dbname"], self::$config["user"], self::$config["pass"]);
        }

        return self::$connexion;
    } 
}