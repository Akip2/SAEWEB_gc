<?php
namespace iutnc\touiter\action;
use iutnc\touiter\touite as Touite;

use iutnc\touiter\render as Render;

require_once "Action.php";


class PublishTouiteAction extends Action {
   
    
    public function execute() : string{
        $contenu;
        if($this->http_method==="GET"){

            $menu=Render\RenderMenu::render();

            $contenu="
            

            <div id='conteneur_principal'>
            <div class=\"menu\">{$menu}</div>

            <div id='conteneur_reste'>
            <h3>Publier un touite</h3>
            <p>(limite de 235 caractères)</p>
            <form method='post' enctype='multipart/form-data' action='?action=publish_touite'>
                <textarea maxlength='235' name=\"texte\" rows=\"4\" cols=\"50\" placeholder=\"Votre touite\" required></textarea> 
                <input class=\"bouton\" type='submit' value='Publier'><br>
                <div class=\"labelUpload\">
                    <label for=\"upload\">Mettre une image dans le touite : </label>
                    <input id=\"upload\" type='file' name='image' accept='.png, .jpg, .jpeg, .gif'>
                </div>

            </form>
            </div>

            </div>
            ";
        }
        else if($this->http_method==="POST"){

            $texte=filter_var($_POST["texte"], FILTER_SANITIZE_SPECIAL_CHARS);
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

            $menu=Render\RenderMenu::render();
            $contenu="
                <div id='conteneur_principal'>
                <div class=\"menu\">{$menu}</div>
                <div id='conteneur_reste'><b>Touite publié avec succès!</b></p></div>
                </div>
            ";
        }

        return $contenu;
    }
    
}