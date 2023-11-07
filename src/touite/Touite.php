<?php

namespace iutnc\touiter\touite;


session_start();
use iutnc\touiter\connection as Connection;
use iutnc\touiter\tag as Tag;


class Touite{
    private string $texte;
    private ?string $chemin_image;

    private string $date_publication;

    public function __construct(string $t, string $date, string $img=null){
        $this->texte=$t;
        $this->chemin_image=$img;

 
        $this->date_publication = $date;

    }
    	


    public function inserer(){
        $bd=Connection\ConnectionFactory::makeConnection();

        //Ajout dans la table Touite
        $st=$bd->prepare("
            INSERT INTO touite(text, image, datePubli, id_auteur) values(?,?,?,?);
        ");

        $st->bindParam(1, $this->texte);
        $st->bindParam(2, $this->chemin_image);
        $st->bindParam(3, $this->date_publication);
        $st->bindParam(4, $_SESSION["user"]->id);

        $st->execute();

        //Stockage de l'id du touite nouvellement ajouté dans une variable
        $st=$bd->prepare("
            SELECT max(id) idTouite FROM touite;
        ");

        $st->execute();
        $data=$st->fetch();

        $id_touite=$data["idTouite"];

        
        //Actions sur le tag
        $tags=$this->getTags();

        foreach($tags as $tag){
            $id_tag=Tag\Tag::getTagId($tag);

            if($id_tag=="none"){  //Le tag nest pas present dans la table Tag

                //Ajout du tag à la table Tag
                $st=$bd->prepare("
                    INSERT INTO tag(libelle) values(?);
                ");
    
                $st->bindParam(1, $this->texte);

                $st->execute();

                //Recuperation de l'id du tag nouvellement ajouté
                $id_tag=Tag\Tag::getTagId($tag);
            }





            //Ajout dans touite2tag
            $st=$bd->prepare("
                INSERT INTO touite2tag(id_touite, id_tag) values(?, ?);
            ");

            $st->bindParam(1, $id_touite);
            $st->bindParam(2, $id_tag);

            $st->execute();

        }       
    }


    public static function getTouiteId(){
        $bd=Connection\ConnectionFactory::makeConnection();

        $st=$bd->prepare(";
        ");
    }



    public function getTags():Array{
        $tags=[];
        $istag=false;
        $tag="";
        foreach (str_split($cthis->texte) as $caractere) {
            if($caractere=="#"){  //Reperage d'un debut de tag
                $istag=true;

                $tag="#";
            }
            else{
                if($istag){
                    if($caractere==" "){ //Fin du tag
                        $tags[]=$tag;
                        $tag="";
                        $istag=false;
                    }
                    else{   //Ajout du caractere au tag
                        $tag=$tag.$caractere;
                    }
                }
            }
        }

        if($tag!=" " && $tag!=""){ //Reperage d'un potentiel tag en fin de texte
            $tags[]=$tag;
        }

        return $tags;
    }
}