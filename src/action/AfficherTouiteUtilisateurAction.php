<?php
namespace iutnc\touiter\action;
use iutnc\touiter\render\RenderListe;
use iutnc\touiter\touite\ListeTouite;
use iutnc\touiter\utilisateur\Utilisateur;

class AfficherTouiteUtilisateurAction extends Action{
	public function execute() : string{
        $id = $_GET["id"];
        $user = new Utilisateur($id);
        $listeTouite = ListeTouite::listeTouiteUser($user->mail);
        $rl = new RenderListe($listeTouite);
        return $rl->render(1);
    }
}