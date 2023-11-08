<?php

namespace iutnc\touiter\tag;
use Exception;
use iutnc\touiter\connection as Connection;

class Tag{
    private $libelle;
    private $description;

    public function __construct(string $lib, string $desc){
        $this->libelle = $lib;
        $this->description = $desc;
    }

    public function __get( string $attr) : mixed {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new Exception("$attr : invalid property");
        }
    
    public function __set( string $attr, mixed $val) : void {
        if (property_exists($this, $attr)) $this->$attr = $val;
        throw new Exception("$attr : invalid property");
    }

    /**
     * Fonction retournant l'id tag d'un libelle donné
     * Si le libelle n'est pas present la fonction renvoit none
     */ 
    public static function getTagId(string $tag):string{
        $bd=Connection\ConnectionFactory::makeConnection();
        $st=$bd->prepare("
            SELECT id FROM tag WHERE libelle=?;
        ");

        $st->bindParam(1, $tag);

        $st->execute();

        $data=$st->fetch();

        if(isset($data["id"])){
            return $data["id"];
        }
        else{
            return "none";
        }
    }
    
}