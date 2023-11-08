<?php

namespace iutnc\touiter\touite;

use Exception;
use iutnc\touiter\connection as Connection;
use iutnc\touiter\tag as Tag;
use iutnc\touiter\touite\ListeTouite;
use iutnc\touiter\render\RenderListe;

class Touite{
    private string $texte;
    private ?string $chemin_image;

    private string $date_publication;

    private int $id_touite;
    private string $nom_auteur;
    private string $prenom_auteur;
    private string $id_auteur;

    public function __construct(int $id_touite){
        $this->id_touite=$id_touite;

        $bd=Connection\ConnectionFactory::makeConnection();

        $st=$bd->prepare("
            SELECT text, chemin, id_auteur, nom, prenom, datePubli FROM touite
            inner join utilisateur on utilisateur.id = id_auteur 
            left join image on touite.id_image = image.id 
            WHERE touite.id=?;
        ");

        $st->bindParam(1, $id_touite);

        $st->execute();

        $data=$st->fetch();

        if($data!=false){
            $this->texte=$data["text"];
            $this->chemin_image=$data["chemin"];
            $this->date_publication=$data["datePubli"];
            $this->nom_auteur=$data["nom"];
            $this->prenom_auteur=$data["prenom"];
            $this->id_auteur=$data["id_auteur"];

        }
        else{
            //AUCUNE LIGNE TROUVEE
        }


    }
    	

    /**
     * Fonction inserant un touite avec les données
     * retourne l'objet Touite inseré
     */
    public static function insererCreer(string $texte, string $date, string $img=null) : Touite{
        $bd=Connection\ConnectionFactory::makeConnection();


        //Ajout dans la table image potentiel
        $id_image=null;

        if($img!=null){
            $st=$bd->prepare("INSERT INTO image(chemin) values(?);");

            $st->bindParam(1, $img);

            $st->execute();


            //Recuperation de l'id de l'image nouvelement ajoutee
            $st=$bd->prepare("
                SELECT max(id) idImage FROM image;
            ");

            $st->execute();

            $data=$st->fetch();
            
            $id_image=$data["idImage"];
        }


        //Ajout dans la table Touite
        $st=$bd->prepare("INSERT INTO touite(text, id_image, datePubli, id_auteur) values(?,?,?,?);");

        $st->bindParam(1, $texte);
        $st->bindParam(2, $id_image);
        $st->bindParam(3, $date);

        $id_auteur=unserialize($_SESSION["user"])->id;
        $st->bindParam(4, $id_auteur);

        $st->execute();

        //Stockage de l'id du touite nouvellement ajouté dans une variable
        $st=$bd->prepare("
            SELECT max(id) idTouite FROM touite;
        ");

        $st->execute();
        $data=$st->fetch();

        $id_touite=$data["idTouite"];

        
        //Actions sur les tag
        $tags=self::getTags($texte);

        foreach($tags as $tag){
            $id_tag=Tag\Tag::getTagId($tag);

            if($id_tag=="none"){  //Le tag nest pas present dans la table Tag

                //Ajout du tag à la table Tag
                $st=$bd->prepare("
                    INSERT INTO tag(libelle) values(?);
                ");
    
                $st->bindParam(1, $tag);

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

        return new Touite($id_touite);
    }



    public static function getTags(string $texte):Array{
        $tags=[];
        $istag=false;
        $tag="";
        foreach (str_split($texte) as $caractere) {
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

    public function __get( string $attr) : mixed {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new Exception("$attr : invalid property");
    }
    
    public static function afficherTouiteAccueil(): string{
        $bd=Connection\ConnectionFactory::makeConnection();
        $req = $bd->prepare("SELECT touite.id, touite.text, utilisateur.prenom, utilisateur.nom FROM touite INNER JOIN utilisateur ON touite.id_auteur = utilisateur.id ORDER BY touite.datePubli DESC;");
        $req->execute();
        
        $listeTouite = new ListeTouite("Touite Accueil");

        while($donnee = $req->fetch()){
            $listeTouite->ajouterTouite(new Touite(intval($donnee[0])));
        }
        $render = new RenderListe($listeTouite);
        return $render->compact();
    }

    public static function supprimerTweet(int $id_touite){
        $bd=Connection\ConnectionFactory::makeConnection();
    
        $st=$bd->prepare("
        DELETE FROM evaluation WHERE id_touite=?
        ");

        $st->bindParam(1, $id_touite);
        $st->execute();
        
        //Supression dans la table touite2tag
        $st=$bd->prepare("
            DELETE FROM touite2tag WHERE id_touite=?
        ");
    
        $st->bindParam(1, $id_touite);
        $st->execute();
    
    
        //Supression dans la table touite
        $st=$bd->prepare("
            DELETE FROM touite WHERE id=?
        ");
    
        $st->bindParam(1, $id_touite);
        $st->execute();
    }
}