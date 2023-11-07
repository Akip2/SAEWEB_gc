<?php
namespace iutnc\touiter\action;
use iutnc\touiter\utilisateur\Utilisateur;
class NoterAction extends Action {
    public function execute() : string{
        if($this->http_method === "GET"){
            $note = Utilisateur::verifierAvis($_GET["idTouite"]);
            if($note !== null){
                if($note > 0){
                    $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter\"/>
                    <input type='radio' name='choix' value='Dislike'>
                    <button type=\"submit\">Valider</button>
                    </form>";
                }else{
                    $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter\"/>
                    <input type='radio' name='choix' value='Like'>
                    <button type=\"submit\">Valider</button>
                    </form>";
                }  
            }else{
                "<form id=\"noter\" method=\"POST\" action=\"?action=noter\"/>
                            <input type='radio' name='choix' value='Like'>
                            <input type='radio' name='choix' value='Dislike'>
                            <button type=\"submit\">Valider</button>
                            </form>";
            }
		}
		elseif($this->http_method === "POST"){
            $page = "<h1> Vous avez donné votre avis merci !</h1>";
		}
		return $page;
    }
}