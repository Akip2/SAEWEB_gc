<?php
namespace iutnc\touiter\action;
use iutnc\touiter\utilisateur\Utilisateur;
class NoterAction extends Action {
    public function execute() : string{
		if($this->http_method === "POST"){
            $page = "<h1> Vous avez donné votre avis merci !</h1>";
            if(isset($_POST["like"])){
                Utilisateur::ajouterAvis(intval($_GET["idTouite"]),1);
            }else if(isset($_POST["dislike"])){
                Utilisateur::ajouterAvis(intval($_GET["idTouite"]),-1);
            }
            else{  //REMOVE DISLIKE OU REMOVE LIKE
                Utilisateur::ajouterAvis(intval($_GET["idTouite"]),0);
            }
		}
		return $page;
    }
}