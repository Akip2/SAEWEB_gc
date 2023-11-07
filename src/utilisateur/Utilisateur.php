<?php

namespace iutnc\touiter\utilisateur;
use Exception;
use iutnc\touiter\connection\ConnectionFactory;

class Utilisateur{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $mail;
    private int $role;

    public function __construct(int $id, string $n, string $p, string $m, int $r){
        $this->id = $id;
        $this->nom = $n;
        $this->prenom = $p;
        $this->mail = $m;
        $this->role = $r;
    }

    
    public static function verifierAvis(int $idTouite) : int{
        $bdd = ConnectionFactory::makeConnection();
		$req = $bdd->prepare("SELECT note FROM evaluation WHERE id_utilisateur = ? and id_touite = ?");
		$req->bindParam(1, $_SESSION["user"]->id);
        $req->bindParam(2, $idTouite);
		$req->execute();
		$donnee = $req->fetch();

        return intval($donnee["note"]);
    }

    public static function ajouterAvis(int $idTouite,int $note) : void{
        $bdd = ConnectionFactory::makeConnection();
        $req = $bdd->prepare("INSERT INTO evaluation(id_touite, id_utilisateur, note) VALUES ($idTouite, {$_SESSION["user"]->id}, $note");
		$req->execute();
    }

    public static function suivreUtilisateur(int $idUtilisateur){
        $bdd = ConnectionFactory::makeConnection();
        $req = $bdd->prepare("INSERT INTO suivreUtilisateur(id_suiveur, id_suivit) VALUES ({$_SESSION["user"]->id}, $idUtilisateur");
		$req->execute();
    }

    public static function nePlusSuivreUtilisateur(int $idUtilisateur){
        $bdd = ConnectionFactory::makeConnection();
        $req = $bdd->prepare("DELETE from suivreUtilisateur where id_suiveur = {$_SESSION["user"]->id} and id_suivit = $idUtilisateur");
		$req->execute();
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