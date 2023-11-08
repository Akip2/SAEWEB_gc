<?php
namespace iutnc\touiter\action;
use iutnc\touiter\utilisateur\Utilisateur;
class NoterAction extends Action {
    public function execute() : string{
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