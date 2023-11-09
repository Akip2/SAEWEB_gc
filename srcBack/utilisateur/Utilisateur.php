<?php

namespace iutnc\backOfficeTouiter\utilisateur;
use Exception;
use iutnc\backOfficeTouiter\connection\ConnectionFactory;
use PDOException;

class Utilisateur{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $mail;
    private int $role;

    public function __construct(int $id){
        $this->id=$id;

        $bd=ConnectionFactory::makeConnection();

        $st=$bd->prepare("
            SELECT nom, prenom, mail, role FROM utilisateur
            WHERE id=?;
        ");

        $st->bindParam(1, $id);

        $st->execute();

        $data=$st->fetch();

        if($data!=false){
            $this->nom=$data["nom"];
            $this->prenom=$data["prenom"];
            $this->mail=$data["mail"];
            $this->role=intval($data["role"]);
        }
    
    }
    public function __get( string $attr) : mixed {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new Exception("$attr : invalid property");
        }
    
    public function __set( string $attr, mixed $val) : void {
        if (property_exists($this, $attr)) $this->$attr = $val;
        throw new Exception("$attr : invalid property");
        }
}