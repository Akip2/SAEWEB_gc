<?php
namespace iutnc\touiter\action;
use iutnc\touiter\utilisateur\Utilisateur;
class NoterAction extends Action {
    public function execute() : string{
        /*if($this->http_method === "GET"){
            $note = Utilisateur::verifierAvis($_GET["idTouite"]);
            if($note !== 0){
                if($note > 0){
                    $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter&idTouite={$_GET["idTouite"]}\"/>
                    <input type='radio' name='choix' value='Dislike'> Dislike
                    <button type=\"submit\">Valider</button>
                    </form>";
                }else{
                    $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter&idTouite={$_GET["idTouite"]}\"/>
                    <input type='radio' name='choix' value='Like'> Like
                    <button type=\"submit\">Valider</button>
                    </form>";

                }  
            }else{
                $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter&idTouite={$_GET["idTouite"]}\"/>
                            <input type='radio' name='choix' value='Like'> Like
                            <input type='radio' name='choix' value='Dislike'> Dislike
                            <button type=\"submit\">Valider</button>
                            </form>";
            }
		}*/
		if($this->http_method === "POST"){
            $page = "<h1> Vous avez donné votre avis merci !</h1>";
            if($_POST["choix"] === "Like"){
                Utilisateur::ajouterAvis(intval($_GET["idTouite"]),1);
            }else{
                Utilisateur::ajouterAvis(intval($_GET["idTouite"]),-1);
            }
		}
		return $page;
    }
}