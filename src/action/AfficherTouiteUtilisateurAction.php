<?php
namespace iutnc\touiter\action;
use iutnc\touiter\render\RenderListe;
use iutnc\touiter\touite\ListeTouite;
class AfficherTouiteUtilisateurAction extends Action{
	public function execute() : string{
        $listeTouite = ListeTouite::listeTouiteUser($_SESSION['user']->mail);
        $rl = new RenderListe($listeTouite);
        return $rl->render(1);
    }
}