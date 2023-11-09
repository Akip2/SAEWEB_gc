<?php

namespace iutnc\backOfficeTouiter\action;

use iutnc\backOfficeTouiter\auth\Auth;
use iutnc\backOfficeTouiter\auth\AuthException;


class ConnexionAction extends Action{
	public function execute() : string{
		if($this->http_method === "GET"){
			$page = "<h2>Connexion</h2><form id=\"connexionUtilisateur\" method=\"POST\" action=\"?action=connexion\"/>  
			<input type=\"email\" name=\"mail\" placeholder=\"votre mail\" required/><br/>
			<input type=\"password\" name=\"mdp\" placeholder=\"mot de passe\" required/><br/>
			<button type=\"submit\">Valider</button>
			</form>";
		}
		elseif($this->http_method === "POST"){
			$mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
			$mdp = filter_var($_POST['mdp'], FILTER_SANITIZE_SPECIAL_CHARS);
			try{
				Auth::authenticate($mail, $mdp);
				$page = "<h4>Bienvenue ".$mail."</h4>";
				$page .= "<p>Actions disponibles :</p>\n<a href=\"?action=listerInfluenceurs\">Afficher utilisateurs influents</a>\n<br><a href=\"?action=listerTags\">Afficher les tags les plus utilisées</a>\n<br><a href=\"?action=deconnexion\">Deconnexion</a>\n";
			}
			catch(AuthException $e){
				$page = "<h3>Un problème est survenue lors de la connexion</h3><p>Message exception : ".$e->getMessage();	
			}
		}
		return $page;
	}
}