<?php

namespace iutnc\touiter\touite;

use Exception;
use iutnc\touiter\connection as Connection;
use iutnc\touiter\tag as Tag;
use iutnc\touiter\touite\ListeTouite;
use iutnc\touiter\render\RenderListe;
use PDOException;

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
            try {
                $st->execute();
            } catch (PDOException) {
                $st=$bd->prepare("
                Delete FROM touite2tag where id_touite = ? and id_tag=?;
            ");

            $st->bindParam(1, $id_touite);
            $st->bindParam(2, $id_tag);
            }
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

                        if(!preg_match("#[\d]#", $tag)){
                            $tags[]=$tag;
                        }
                        $tag="";
                        $istag=false;
                    }
                    else{   //Ajout du caractere au tag
                        $tag=$tag.$caractere;
                    }
                }
            }
        }

        if($tag!=" " && $tag!="" && !preg_match("#[\d]#", $tag)){ //Reperage d'un potentiel tag en fin de texte
            $tags[]=$tag;
        }

        return $tags;
    }

    public static function getTagId(string $tag) : int|null {
        $bd=Connection\ConnectionFactory::makeConnection();
        
        $st=$bd->prepare("SELECT id FROM tag WHERE libelle = ?;");

        $st->bindParam(1, $tag);

        $st->execute();

        $data=$st->fetch();

        if($data===false){
            return null;
        }
        else{
            return $data["id"];
        }
        

    }

    public function __get( string $attr) : mixed {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new Exception("$attr : invalid property");
    }
    
    public static function afficherTouiteAccueil(): string{
        $bd=Connection\ConnectionFactory::makeConnection();
        $req = $bd->prepare("SELECT touite.id, touite.text, utilisateur.prenom, utilisateur.nom FROM touite INNER JOIN utilisateur ON touite.id_auteur = utilisateur.id ORDER BY touite.datePubli DESC;");
        $req->execute();
        
        $listeTouite = new ListeTouite("");

        while($donnee = $req->fetch()){
            $listeTouite->ajouterTouite(new Touite(intval($donnee[0])));
        }
        $render = new RenderListe($listeTouite);
        return $render->compact();
    }

    public static function afficherMurAccueil(): string{
        $bd=Connection\ConnectionFactory::makeConnection();

        $req = $bd->prepare("SELECT id, datePubli
FROM (
    SELECT id, datePubli
    FROM touite
    WHERE id_auteur IN (
        SELECT id_suivit
        FROM suivreUtilisateur
        WHERE id_suiveur = ?
    )
    UNION
    SELECT id_touite, datePubli
    FROM touite2tag
    INNER JOIN touite ON touite.id = touite2tag.id_touite
    WHERE id_tag IN (
        SELECT id_tag
        FROM suivretag
        WHERE id_suiveur = ?
    )
) AS tempTable order by tempTable.datePubli DESC;");

        $idu = unserialize($_SESSION['user'])->id;
        $req->bindParam(1, $idu);
        $req->bindParam(2, $idu);
        $req->execute();
        
        $listeTouite = new ListeTouite("<h4>Votre Mur</h4>");

        while($donnee = $req->fetch()){
            if (!in_array(new Touite(intval($donnee[0])) ,$listeTouite->liste)){
                $listeTouite->ajouterTouite(new Touite(intval($donnee[0])));
            }
        }

        $render = new RenderListe($listeTouite);
        return $render->compact();
    }

    public static function supprimerTweet(int $id_touite){
        $bd=Connection\ConnectionFactory::makeConnection();

        $st=$bd->prepare("SELECT id_image from touite where id = ?");

        $st->bindParam(1, $id_touite);
        $st->execute();
        $donnee = $st->fetch();
        $idImage =  intval($donnee['id_image']);

        $st->bindParam(1, $id_touite);
        $st->execute();
    
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

        $st=$bd->prepare("SELECT chemin FROM image WHERE id=?");

        $st->bindParam(1, $idImage);
        $st->execute();

        $donnee = $st->fetch();

        if(isset($donne["chemin"])){
            $cheminImg =  $donnee['chemin'];

            unlink($cheminImg);

            $st=$bd->prepare("DELETE FROM image WHERE id=?");

            $st->bindParam(1, $idImage);
            $st->execute();
        }
    }
    
    public function noteTouite(): int{
        $bd=Connection\ConnectionFactory::makeConnection();
    
        $st=$bd->prepare("SELECT SUM(evaluation.note)FROM evaluation WHERE evaluation.id_touite = :pIdTouite;");
        $st->bindParam(":pIdTouite", $this->id_touite);
        $st->execute();
        $donnee = $st->fetch();
        if($donnee === false){
            return 0;
        }
        return intval($donnee[0]);
    }
}