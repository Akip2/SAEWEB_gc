<?php

namespace iutnc\touiter\auth;

use iutnc\touiter\utilisateur\Utilisateur;
use iutnc\touiter\connection\ConnectionFactory;

class Auth{
	public static function authenticate(string $mail, string $passwd): void{
		$bdd = ConnectionFactory::makeConnection();
		$req = $bdd->prepare("SELECT id, nom, prenom, mail, motdepasse, role FROM utilisateur WHERE mail = :pemail");
		$req->bindParam(":pemail", $mail);
		$req->execute();
		$donnee = $req->fetch();
		//var_dump($donnee);
		if(!is_null($donnee["motdepasse"])){
			//$hash = password_hash($passwd, PASSWORD_DEFAULT, ["cost" => 12]);
			//print($hash."   <br>".$donnee["passwd"]);
			if(!password_verify($passwd, $donnee["motdepasse"])){
				throw new AuthException("invalid motdepasse");
			}
			else{
				$u = new Utilisateur(intval($donnee["id"]), $donnee["nom"], $donnee["prenom"], $donnee["mail"], intval($donnee["role"]));
				$_SESSION['user'] = $u;
			}
		}
		else{
			throw new AuthException("user non inexisant");
		}
	}
	public static function register(string $nom, string $prenom, string $mail, string $passwd): void{
		$hash = password_hash($passwd, PASSWORD_DEFAULT, ["cost" => 12]);
		$bdd = ConnectionFactory::makeConnection();
		$req = $bdd->prepare("SELECT COUNT(mail) FROM utilisateur WHERE mail LIKE :pemail;");
		$req->bindParam(":pemail", $mail);
		$req->execute();
		$donnee = $req->fetch();
		if(!($donnee[0] === 0)){
			throw new AuthException;
		}
		$insertion = $bdd->exec("INSERT INTO utilisateur(nom, prenom, mail, motdepasse,role) VALUES (\"".$nom."\",\"".$prenom."\",\"".$mail."\",\"".$hash."\",1);");
	}
}