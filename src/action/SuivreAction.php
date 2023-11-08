<?php
namespace iutnc\touiter\action;

use iutnc\touiter\utilisateur\Utilisateur;

class ShowTouiteAction extends Action{
	public function execute() : string{
        if ($_GET['suivre'] === 1){
            Utilisateur::suivreUtilisateur($_GET['idUtilisateur']);
            return "Vous vous êtes bien abonné";
        }else{
            Utilisateur::nePlusSuivreUtilisateur($_GET['idUtilisateur']);
            return "Vous vous êtes bien désabonné";
        }
        
        
	}
}