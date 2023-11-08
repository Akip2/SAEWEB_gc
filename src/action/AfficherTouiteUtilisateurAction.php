<?php
namespace iutnc\touiter\action;
use iutnc\touiter\render\RenderListe;
use iutnc\touiter\touite\ListeTouite;
use iutnc\touiter\utilisateur\Utilisateur;

class AfficherTouiteUtilisateurAction extends Action{
	public function execute() : string{
        $id = $_GET["id"];
        $listeTouite = ListeTouite::listeTouiteUser($id);
        $rl = new RenderListe($listeTouite);
        return $rl->render(1);
    }
}