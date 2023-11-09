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
		if($donnee!==false){
			//$hash = password_hash($passwd, PASSWORD_DEFAULT, ["cost" => 12]);
			//print($hash."   <br>".$donnee["passwd"]);
			if(!password_verify($passwd, $donnee["motdepasse"])){
				throw new AuthException("invalid motdepasse");
			}
			else{
				$u = new Utilisateur(intval($donnee["id"]));
				$_SESSION['user'] = serialize($u);
			}
		}
		else{
			throw new AuthException("user non inexisant");
		}
	}
	public static function register(string $nom, string $prenom, string $mail, string $passwd): void{
		$hash = password_hash($passwd, PASSWORD_DEFAULT, ["cost" => 12]);
		$bdd = ConnectionFactory::makeConnection();
		
		$req = $bdd->prepare("SELECT mail FROM utilisateur WHERE mail=?;");
		$req->bindParam(1, $mail);
		$req->execute();
		$donnee = $req->fetch();

		//var_dump($donnee);

		if($donnee!==false){
			throw new AuthException;
		}
		$insertion = $bdd->exec("INSERT INTO utilisateur(nom, prenom, mail, motdepasse,role) VALUES (\"".$nom."\",\"".$prenom."\",\"".$mail."\",\"".$hash."\",1);");
	}
	public static function disconnection(): void{
		if(isset($_SESSION['user'])){
			unset($_SESSION['user']);
		}
	}
}