<?php

namespace iutnc\backOfficeTouiter\action;

use iutnc\backOfficeTouiter\auth\Auth;
use iutnc\backOfficeTouiter\auth\AuthException;
use iutnc\backOfficeTouiter\connection\ConnectionFactory;
class ListerInfluenceursAction extends Action{
	public function execute() : string{
		$page = "<h4>Voici les données:</h4>";
		$bdd = ConnectionFactory::makeConnection();
		$req = $bdd->query("SELECT utilisateur.id, utilisateur.prenom, utilisateur.nom, COUNT(suivreUtilisateur.id_suivit) FROM utilisateur LEFT JOIN suivreUtilisateur ON utilisateur.id = suivreUtilisateur.id_suivit GROUP BY utilisateur.id ORDER BY COUNT(suivreUtilisateur.id_suivit) DESC;");
		while($donnees = $req->fetch()){
			$page .= "Utilisateur : ".$donnees["prenom"]." ".$donnees["nom"]." , nombre de suiveurs : ".$donnees[3]."</p>";
		}
		return $page;
	}
}