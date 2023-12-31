<?php

namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\auth\AuthException;

use iutnc\touiter\connection as Connection;

use iutnc\touiter\render\RenderMenu;

class FollowTagAction extends Action{
	public function execute() : string{
		$menu=RenderMenu::render();


		if(isset($_SESSION["user"])){
			$bd=Connection\ConnectionFactory::makeConnection();


			$u=unserialize($_SESSION["user"]);
			$id_utilisateur=$u->id;


			if($this->http_method === "GET"){

				$st=$bd->prepare("
					SELECT libelle, id FROM tag;
				");
				$st->execute();

				$page="<div id='conteneur_principal'><div class='menu'>$menu</div><form id='suivreTag' method='POST' action='?action=follow_tag'>";


				while($data=$st->fetch()){
					$lib=$data["libelle"];
					$id_tag=$data["id"];

					$st2=$bd->prepare("
						SELECT id_tag FROM suivretag WHERE id_suiveur=? AND id_tag=?;
					");
					$st2->bindParam(1, $id_utilisateur);
					$st2->bindParam(2, $id_tag);

					$st2->execute();
					$data2=$st2->fetch();

					if($data2==false){  //l'utilisateur n'est pas abonné au tag
						$page.="$lib : <input type='checkbox' name='$id_tag'/>";
					}
					else{
						$page.="$lib : <input type='checkbox' name='$id_tag' checked/>";
					}
					$page.="</br>";
				}

				$page .= "<input type='submit' class='bouton' value='Enregistrer mes préférences'/></form></div>";
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
				
				$page="
					<div id='conteneur_principal'>
					<div class='menu'>$menu</div>
					<p>Vos préférences ont été enregistrées</p>
					</div>
				";
			}
		}
		else{
			$page="<p>Vous devez vous authentifier pour accèder à cette page.</p>";
		}

		return $page;
	}
}