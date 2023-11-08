<?php
namespace iutnc\touiter\action;
use iutnc\touiter\render\RenderListe;
use iutnc\touiter\touite\ListeTouite;
class AfficherTouiteUtilisateurAction extends Action{
	public function execute() : string{
        $u = unserialize($_SESSION['user']);
        $listeTouite = ListeTouite::listeTouiteUser($u->mail);
        $rl = new RenderListe($listeTouite);
        return $rl->render(1);
    }
}