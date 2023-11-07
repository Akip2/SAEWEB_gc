<?php

namespace iutnc\touiter\touite;
session_start();
use iutnc\touiter\connection as Connection;

class Touite{
    private string $texte;
    private ?string $chemin_image;

    private string $date_publication;

    public function __construct(string $t, string $img=null){
        $this->texte=$t;
        $this->chemin_image=$img;

        
        //date de publication 
        date_default_timezone_set('Europe/Paris');
        $this->date_publication = date('d-m-Y H:i:s');

    }


    public function inserer(){
        $bd=Connection\ConnectionFactory::makeConnection();

        $st=$bd->prepare("
            INSERT INTO touite(text, image, datePubli, id_auteur) values(?,?,?,?);
        ");

        $st->bindParam(1, $this->texte);
        $st->bindParam(2, $this->chemin_image);
        $st->bindParam(3, $this->date_publication);
        $st->bindParam(4, $_SESSION["user"]->id);

        $st->execute();
    }
}