<?php
namespace iutnc\touiter\action;
use iutnc\touiter\utilisateur\Utilisateur;
class NoterAction extends Action {
    public function execute() : string{
        if($this->http_method === "GET"){
            $note = Utilisateur::verifierAvis($_GET["idTouite"]);
            echo $note;
            if($note !== 0){
                if($note > 0){
                    $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter\"/>
                    <input type='radio' name='choix' value='Dislike'> Dislike :(
                    <button type=\"submit\">Valider</button>
                    </form>";
                }else{
                    $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter\"/>
                    <input type='radio' name='choix' value='Like'> Like :D
                    <button type=\"submit\">Valider</button>
                    </form>";
                }  
            }else{
                $page = "<form id=\"noter\" method=\"POST\" action=\"?action=noter\"/>
                            <input type='radio' name='choix' value='Like'> Like >:)
                            <input type='radio' name='choix' value='Dislike'> Dislike TwT
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