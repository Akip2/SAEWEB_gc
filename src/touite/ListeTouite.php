<?php

namespace iutnc\touiter\touite;
use iutnc\touiter\connection\ConnectionFactory;
use iutnc\touiter\touite\Touite;

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
    

    public static function listeTouiteTag(string $tag) : ListeTouite {
        $bd=ConnectionFactory::makeConnection();
        $listes = new ListeTouite("Touites associé au tag : ".$tag);

        $st=$bd->prepare("
           Select touite.id from tag ta inner join touite2tag tt 
                inner join on ta.id = tt.id_tag 
                inner join touite to on to.id = tt.id_touite
                left join image on image.id = to.id_image
                where ta.libelle = ? order by datePubli DESC;
        ");

        $st->bindParam(1, $tag);

        $st->execute();
        while($data=$st->fetch()) {
            $listes->ajouterTouite(new Touite($data["id"]));
        }
        return $listes;
    }

    public static function listeTouiteUser(string $user) : ListeTouite {
        $bd=ConnectionFactory::makeConnection();
        $listes = new ListeTouite("Touites posté par l'utilisateur : ".$tag);

        $st=$bd->prepare("
            Select touite.id from touite 
                inner join utilisateur on touite.id_auteur = utilisateur.id  
                left join image on image.id = touite.id_image 
                where utilisateur.mail = ? order by datePubli DESC;
            ");

        $st->bindParam(1, $user);

        $st->execute();
        while($data=$st->fetch()) {
            $listes->ajouterTouite(new Touite($data["id"]));
        }
        return $listes;
    }
}