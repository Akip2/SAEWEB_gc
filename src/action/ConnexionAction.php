<?php

namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\auth\AuthException;
use iutnc\touiter\touite\ListeTouite;
use iutnc\touiter\render\RenderListe;

class ConnexionAction extends Action{
	public function execute() : string{
		if($this->http_method === "GET"){
			$page = "<h2>Connexion</h2><form id=\"connexionUtilisateur\" method=\"POST\" action=\"?action=connexion\"/>  
			<input class=\"inputPass\" type=\"email\" name=\"mail\" placeholder=\"votre mail\" required/><br/>
			<input class=\"inputPass\" type=\"password\" name=\"mdp\" placeholder=\"mot de passe\" required/><br/>
			<button type=\"submit\">Valider</button>
			</form>";
		}
		elseif($this->http_method === "POST"){
			$mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
			$mdp = filter_var($_POST['mdp'], FILTER_SANITIZE_SPECIAL_CHARS);
			try{
				Auth::authenticate($mail, $mdp);
				$page = "<h2>Bienvenue ".$mail."</h2><br/><p>Voici vos touites:</p><br>";
				$u = unserialize($_SESSION['user']);
				$listeTouite = ListeTouite::listeTouiteUser($u->id);
                $rl = new RenderListe($listeTouite);
                $page = $page.$rl->render(1);
			}
			catch(AuthException $e){
				$page = "<h3> Mot de passe ou identifiant incorrect</h3><br/>";	
			}
		}
		return $page;
	}
}