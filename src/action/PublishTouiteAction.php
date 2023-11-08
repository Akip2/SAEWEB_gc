<?php
namespace iutnc\touiter\action;
use iutnc\touiter\touite as Touite;

require_once "Action.php";


class PublishTouiteAction extends Action {
   
    
    public function execute() : string{
        $contenu;
        if($this->http_method==="GET"){
            $contenu="
            <form method='post' enctype='multipart/form-data' action='?action=publish_touite'>
                <label>Texte :</label>
                <textarea name=\"texte\" rows=\"4\" cols=\"50\" required></textarea> 
                <input type='submit' value='Publier'>
                <input type='file' name='image' accept='.png, .jpg, .jpeg, .gif'>
            </form>
            ";
        }
        else if($this->http_method==="POST"){

            $texte=filter_var($_POST["texte"], FILTER_DEFAULT);
            if(strlen($texte)<=235){
                //date de publication 
                date_default_timezone_set('Europe/Paris');
                $date_publication = date('Y-m-d H:i:s');
                //print($date_publication);
                
                $touite; //Initialisation du touite

                $dest=null;
                if($_FILES["image"]["tmp_name"]!=null){

                    $tmp=$_FILES["image"]["tmp_name"];

                    $filename=uniqid();
                    
                    $dest="img/$filename.png"; //Stockage de l'image côté serveur

                    move_uploaded_file($tmp, $dest);
                }

                //Insertion du touite
                $touite=Touite\Touite::insererCreer($texte, $date_publication, $dest);
                $contenu="<b>Touite publié avec succès!</b></br><p>(normalement ^^')</p>";
            }
            else{
                $contenu="<b>Touite trop long !</b></br>";
            }
        }

        return $contenu;
    }
    
}