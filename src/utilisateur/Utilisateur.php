<?php

namespace iutnc\touiter\utilisateur;
use Exception;
use iutnc\touiter\connection\ConnectionFactory;
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
            $this->role=$data["role"];
        }
    
    }

    public static function verifierAvis(int $idTouite): int{
        $bdd = ConnectionFactory::makeConnection();
		$req = $bdd->prepare("SELECT note FROM evaluation WHERE id_utilisateur = ? and id_touite = ?");
        $u = unserialize($_SESSION["user"]);
		$req->bindParam(1, $u->id);
        $req->bindParam(2, $idTouite);
		$req->execute();
		$donnee = $req->fetch();
        if($donnee === false){
            return 0;
        }
        return intval($donnee["note"]);
    }
    public static function ajouterAvis(int $idTouite,int $note) : void{
        $bdd = ConnectionFactory::makeConnection();
        $u = unserialize($_SESSION["user"]);
        $req = $bdd->prepare("INSERT INTO evaluation(id_touite, id_utilisateur, note) VALUES (?, ?, ?)");
        $req->bindParam(1, $idTouite);
        $req->bindParam(2, $u->id);
        $req->bindParam(3, $note);
        try{
            $req->execute();
        }catch(PDOException $e){
            $req = $bdd->prepare("UPDATE evaluation set note = ? where id_touite = ? and id_utilisateur = ?");
            $req->bindParam(1, $note);
            $req->bindParam(2, $idTouite);
            $req->bindParam(3, $u->id);
            $req->execute();
        }

		
    }
    public static function utilisateurNarcissique():string{
        if(isset($_SESSION['user'])){
            $bdd = ConnectionFactory::makeConnection();
            $req = $bdd->prepare("SELECT utilisateur.prenom, utilisateur.nom FROM utilisateur INNER JOIN suivreUtilisateur ON utilisateur.id = suivreUtilisateur.id_suiveur WHERE suivreUtilisateur.id_suivit = :pidUtilisateur;");
            $u = unserialize($_SESSION['user']);
            $req->bindParam(":pidUtilisateur", $u->id);
            $req->execute();
            
            $html ="<div class=\"menuStats\"><p>Vos suiveurs:</p><ul>\n";
            while($donnee = $req->fetch()) {
                $html = $html."<li>".$donnee["prenom"]." ".$donnee["nom"]."</li>";
            }
            $html= $html."</ul><br><p>Moyenne de vos touites:\n";
            $reqNbTouite = $bdd->prepare("SELECT COUNT(touite.id_auteur) FROM touite WHERE touite.id_auteur = :pidUtilisateur;");
            $reqNbTouite->bindParam(":pidUtilisateur", $u->id);
            $reqNbTouite->execute();
            $donnee = $reqNbTouite->fetch();
            if($donnee == false || is_null($donnee)){
                $nbTouite = 0;    
            }
            else{
                $nbTouite = intval($donnee[0]);
            }
            //var_dump($donnee);
            if($nbTouite !== 0){
                $reqSommeTouite = $bdd->prepare("SELECT SUM(evaluation.note) FROM evaluation
                    INNER JOIN touite ON evaluation.id_touite = touite.id
                    WHERE touite.id_auteur = :pidUtilisateur;");
                $reqSommeTouite->bindParam(":pidUtilisateur", $u->id);
                $reqSommeTouite->execute();
                $donnee = $reqSommeTouite->fetch();
                if($donnee == false || is_null($donnee)){
                    $sommeTouite = 0;    
                }
                else{
                    $sommeTouite = intval($donnee[0]);
                }
                $sommeTouite = intval($donnee[0]);
                $html = $html.(round($sommeTouite/$nbTouite, 2))." likes par touite </p>";
            }
            return $html."</div>";
        }else{
            return "";
        }
    }

    public static function verifierSuivi(int $idUtilisateur) : bool{
        $bdd = ConnectionFactory::makeConnection();
        $req = $bdd->prepare("SELECT count(*) as suivre from suivreUtilisateur where id_suiveur = ? and id_suivit = ?");
        $u = unserialize($_SESSION["user"]);
        $req->bindParam(1, $u->id);
        $req->bindParam(2, $idUtilisateur);
		$req->execute();
        $donnee = $req->fetch();
        return $donnee["suivre"] > 0;
    }
    public static function suivreUtilisateur(int $idUtilisateur) : void{
        $bdd = ConnectionFactory::makeConnection();
        $req = $bdd->prepare("INSERT INTO suivreUtilisateur(id_suiveur, id_suivit) VALUES (?, ?)");
        $u = unserialize($_SESSION["user"]);
        $req->bindParam(1, $u->id);
        $req->bindParam(2, $idUtilisateur);
		$req->execute();
    }

    public static function nePlusSuivreUtilisateur(int $idUtilisateur) : void{
        $bdd = ConnectionFactory::makeConnection();
        $req = $bdd->prepare("DELETE from suivreUtilisateur where id_suiveur = ? and id_suivit = ?");
        $u = unserialize($_SESSION["user"]);
        $req->bindParam(1, $u->id);
        $req->bindParam(2, $idUtilisateur);
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