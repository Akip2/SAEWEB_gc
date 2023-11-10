<?php

namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\auth\AuthException;

class InscriptionAction extends Action{
	public function execute() : string{
		if($this->http_method === "GET"){
				$page = "<form id=\"inscription\" method=\"POST\" action=\"?action=inscription\">
				<p>Veuillez renseigner votre mail</p>  
				<input class=\"inputPass\" type=\"email\" name=\"mail\" placeholder=\"votre mail\" required/><br>
				<p>Veuillez renseigner votre nom et votre prenom</p>
				<input class=\"inputPass\" type=\"text\" name=\"nom\" placeholder=\"votre nom\" required/>
				<input class=\"inputPass\" type=\"text\" name=\"prenom\" placeholder=\"votre prenom\" required/><br>
				<p>Veuillez renseigner votre mot de passe</p>
				<input class=\"inputPass\" type=\"password\" name=\"mdp1\" placeholder=\"mot de passe\" required/>
				<input class=\"inputPass\" type=\"password\" name=\"mdp2\" placeholder=\"mot de passe\" required/><br>
				<button type=\"submit\">Valider</button>
				</form>";
		}
		elseif($this->http_method === "POST"){
			$mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
			$nom = filter_var($_POST['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
			$prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_SPECIAL_CHARS);
			$mdp1 = filter_var($_POST['mdp1'], FILTER_SANITIZE_SPECIAL_CHARS);
			$mdp2 = filter_var($_POST['mdp2'], FILTER_SANITIZE_SPECIAL_CHARS);	
			
			//test d'admissibilté du mdp
			$length = (strlen($mdp1) >= 8); // longueur minimale
			$chiffre = preg_match("#[\d]#", $mdp1); // au moins un chiffre
			$special = preg_match("#[\W]#", $mdp1); // au moins un car. spécial
			$lower = preg_match("#[a-z]#", $mdp1); // au moins une minuscule
			$upper = preg_match("#[A-Z]#", $mdp1); // au moins une majuscule

			if($length && $chiffre && $special && $lower && $upper){
				if(($mdp1 === $mdp2)){
					try{
						Auth::register($nom, $prenom, $mail, $mdp1);
						$page = "<h2 style=\"text-align:center;margin-top: 5%;\">Inscription réussie !</h1>";
					}
					catch(AuthException $e){
						$page = "<h2 style=\"text-align:center;margin-top: 5%;\">L'inscription a échoué, l'email est déjà utilisé</h1>";
					}
				}
				else{
					$page = "<h2 style=\"text-align:center;margin-top: 5%;\">Les mots de passes sont différents</h2>";
				}
			}
			else{
				$page = "<h2 style=\"text-align:center;margin-top: 5%;\">Veuillez entrer un mot de passe plus sécurisé</h2>";
			}
		}
		return $page;
	}
}