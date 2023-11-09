<?php

namespace iutnc\backOfficeTouiter\action;

use iutnc\backOfficeTouiter\auth\Auth;
use iutnc\backOfficeTouiter\auth\AuthException;
use iutnc\backOfficeTouiter\connection\ConnectionFactory;
class ListerTagsAction extends Action{
	public function execute() : string{
		$page = "<h4>Voici les données:</h4>";
		$bdd = ConnectionFactory::makeConnection();
		$req = $bdd->query("SELECT tag.id, tag.libelle, COUNT(suivretag.id_tag) FROM tag LEFT JOIN suivretag ON tag.id = suivretag.id_tag GROUP BY tag.id ORDER BY COUNT(suivretag.id_tag) DESC;");
		while($donnees = $req->fetch()){
			$page .= "<p>Tag : ".$donnees["libelle"]." , nombre de mention du tag : ".$donnees[2]."</p>";
		}
		return $page;
	}
}