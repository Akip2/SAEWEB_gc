<?php

namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\auth\AuthException;

use iutnc\touiter\connection as Connection;

class FollowTagAction extends Action{
	public function execute() : string{
		$bd=Connection\ConnectionFactory::makeConnection();
		$id_utilisateur=$_SESSION["user"]->id;




		if($this->http_method === "GET"){

			$st=$bd->prepare("
				SELECT libelle, id FROM tag;
			");
			$st->execute();

			$page="<form id='suivreTag' method='POST' action='?action=follow_tag'>";


			while($data=$st->fetch()){
				$lib=$data["libelle"];
				$id_tag=$data["id"];

				$st=$bd->prepare("
					SELECT id_tag FROM suivretag WHERE id_suiveur=? AND id_tag=?;
				");
				$st->bindParam(1, $id_utilisateur);
				$st->bindParam(2, $id_tag);

				$st->execute();
				$data=$st->fetch();

				if($data==false){  //l'utilisateur n'est pas abonné au tag
					$page.="$lib : <input type='checkbox' name='$id_tag'/>";
				}
				else{
					$page.="$lib : <input type='checkbox' name='$id_tag' checked/>";
				}
				$page.="</br>";
			}

			$page .= "<input type='submit' value='Enregistrer mes préférences'/></form>";
		}
		elseif($this->http_method === "POST"){

			//On cherche a connaitre les tags deja suivi
			$st=$bd->prepare("
				SELECT id_tag FROM suivretag WHERE id_suiveur=?;
			");

			$st->bindParam(1, $id_utilisateur);
			$st->execute();

			$suivi=[];
			while($data=$st->fetch()){
				$suivi[]=$data["id_tag"];
			}

			//On va regarder la selection de l'utilisateur sur chaque tag
			$st=$bd->prepare("
				SELECT id FROM tag;
			");
			$st->execute();

			//Preparation du statement d'ajout d'abonnement 
			$add_statement=$bd->prepare("
				INSERT INTO suivretag(id_suiveur, id_tag) values(?,?);
			");
			$add_statement->bindParam(1, $id_utilisateur);

			//Preparation du statement de désabonnement 
			$remove_statement=$bd->prepare("
				DELETE FROM suivretag WHERE id_suiveur=? AND id_tag=?;
			");
			$remove_statement->bindParam(1, $id_utilisateur);

			while($data=$st->fetch()){
				$id_tag=$data["id"];
				if(isset($_POST[$id_tag]) && in_array($id_tag, $suivi)==false){ //Tag checké et non suivi
					//On met a jour la bd

					$add_statement->bindParam(2, $id_tag);
					$add_statement->execute();
				}
				elseif(isset($_POST[$id_tag])==false && in_array($id_tag, $suivi)){ //Tag non-checké mais suivi
					//On met a jour la bd

					$remove_statement->bindParam(2, $id_tag);
					$remove_statement->execute();
				}
			}

			$page="<p>Vos préférences ont été enregistrées</p>";
		}

		return $page;
	}
}