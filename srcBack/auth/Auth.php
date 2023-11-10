<?php

namespace iutnc\backOfficeTouiter\auth;

use iutnc\backOfficeTouiter\utilisateur\Utilisateur;
use iutnc\backOfficeTouiter\connection\ConnectionFactory;

class Auth{
	public static function authenticate(string $mail, string $passwd): void{
		$bdd = ConnectionFactory::makeConnection();
		$req = $bdd->prepare("SELECT id, nom, prenom, mail, motdepasse, role FROM utilisateur WHERE mail = :pemail");
		$req->bindParam(":pemail", $mail);
		$req->execute();
		$donnee = $req->fetch();
		//var_dump($donnee);
		if($donnee!==false){
			if(intval($donnee["role"]) !== 100){
				throw new AuthException("Le compte n'est pas administrateur");
			}
			if(!password_verify($passwd, $donnee["motdepasse"])){
				throw new AuthException("invalid motdepasse");
			}
			else{
				$u = new Utilisateur(intval($donnee["id"]));
				$_SESSION['user'] = serialize($u);
			}
		}
		else{
			throw new AuthException("Utilisateur absent de la base de donnee");
		}
	}

	public static function register(string $nom, string $prenom, string $mail, string $passwd): void{
		$hash = password_hash($passwd, PASSWORD_DEFAULT, ["cost" => 12]);
		$bdd = ConnectionFactory::makeConnection();
		$req = $bdd->prepare("SELECT COUNT(mail) FROM utilisateur WHERE mail LIKE :pemail;");
		$req->bindParam(":pemail", $mail);
		$req->execute();
		$donnee = $req->fetch();
		if(!(intval($donnee[0]) === 0)){
			//echo $donnee[0];
			throw new AuthException("L'admin existe déjà");
		}
		$insertion = $bdd->exec("INSERT INTO utilisateur(nom, prenom, mail, motdepasse,role) VALUES (\"".$nom."\",\"".$prenom."\",\"".$mail."\",\"".$hash."\",100);");
	}

	public static function disconnection(): void{
		if(isset($_SESSION['user'])){
			unset($_SESSION['user']);
		}
	}
}