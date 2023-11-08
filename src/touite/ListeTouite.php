<?php

namespace iutnc\touiter\touite;
use Exception;
use iutnc\touiter\connection\ConnectionFactory;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\utilisateur\Utilisateur;

class ListeTouite{
    private array $liste; 
    private string $name;

    public function __construct(string $n) {
        $this->name = $n;
        $this->liste = array();
    }

    public function __get( string $attr) : mixed {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new Exception("$attr : invalid property");
        }

    public function ajouterTouite(Touite $t) {
        array_push($this->liste,$t);
    }
    

    public static function listeTouiteTag(int $id) : ListeTouite {
        $bd=ConnectionFactory::makeConnection();
        $st=$bd->prepare("
           Select touite.id from tag ta inner join touite2tag tt 
                inner join on ta.id = tt.id_tag 
                inner join touite to on to.id = tt.id_touite
                left join image on image.id = to.id_image
                where ta.id = ? order by datePubli DESC;
        ");

        $st->bindParam(1, $id);

        $st->execute();
        while($data=$st->fetch()) {
            $listes->ajouterTouite(new Touite($data["id"]));
        }
        return $listes;
    }

    public static function listeTouiteUser(int $user) : ListeTouite {

        $bd=ConnectionFactory::makeConnection();
        $u = new Utilisateur($user);
        $listes = new ListeTouite("Touites posté par l'utilisateur : ".$u->mail);
        $st=$bd->prepare("
            Select touite.id from touite 
                inner join utilisateur on touite.id_auteur = utilisateur.id  
                left join image on image.id = touite.id_image 
                where utilisateur.id = ? order by datePubli DESC;
            ");

        $st->bindParam(1, $user);

        $st->execute();
        while($data=$st->fetch()) {
            $listes->ajouterTouite(new Touite($data["id"]));
        }
        return $listes;
    }
}