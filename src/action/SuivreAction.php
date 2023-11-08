<?php
namespace iutnc\touiter\action;

use iutnc\touiter\utilisateur\Utilisateur;

class SuivreAction extends Action{
	public function execute() : string{
        if (intval($_GET['suivre']) === 0){
            Utilisateur::suivreUtilisateur($_GET['idUtilisateur']);
            return "Vous vous êtes bien abonné";
        }else{
            Utilisateur::nePlusSuivreUtilisateur($_GET['idUtilisateur']);
            return "Vous vous êtes bien désabonné";
        }
        
        
	}
}