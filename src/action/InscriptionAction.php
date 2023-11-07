<?php

namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;

class InscriptionAction extends Action{
	public function execute() : string{
		if($this->http_method === "GET"){
				$page = "<form id=\"inscription\" method=\"POST\" action=\"?action=inscription\"/>
				<p>Veuillez renseigner votre mail</p>  
				<input type=\"email\" name=\"mail\"/><br>
				<p>Veuillez renseigner votre nom et votre prenom</p>
				<input type=\"text\" name=\"nom\"/>
				<input type=\"text\" name=\"prenom\"/><br>
				<p>Veuillez renseigner votre mot de passe</p>
				<input type=\"password\" name=\"mdp1\"/>
				<input type=\"password\" name=\"mdp2\"/><br>
				<button type=\"submit\">Valider</button>
				</form>";
		}
		elseif($this->http_method === "POST"){
			$mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
			$nom = filter_var($_POST['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
			$prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_SPECIAL_CHARS);
			$mdp1 = filter_var($_POST['mdp1'], FILTER_SANITIZE_SPECIAL_CHARS);
			$mdp2 = filter_var($_POST['mdp2'], FILTER_SANITIZE_SPECIAL_CHARS);	
			if(($mdp1 === $mdp2) && (strlen($mdp1) >= 4)){
				try{
					Auth::register($nom, $prenom, $mail, $mdp1);
					$page = "<h2 style=\"text-align:center;margin-top: 5%;\">Inscription réussie !</h1>";
				}
				catch(AuthException $e){
					$page = "<h2 style=\"text-align:center;margin-top: 5%;\">L'inscription a échoué, l'email est déjà utilisé</h1>";
				}
			}
			else{
				$page = "<h2 style=\"text-align:center;margin-top: 5%;\">Les mots de passes sont différents et/ou trop courts</h2>";
			}
		}
		return $page;
	}
}