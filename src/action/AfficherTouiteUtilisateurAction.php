<?php
namespace iutnc\touiter\action;
use iutnc\touiter\render\RenderListe;
use iutnc\touiter\touite\ListeTouite;
use iutnc\touiter\utilisateur\Utilisateur;

class AfficherTouiteUtilisateurAction extends Action{
	public function execute() : string{
        $u = unserialize($_SESSION['user']);
        $id = $u->id;
        $user = new Utilisateur($id);
        $listeTouite = ListeTouite::listeTouiteUser($user->mail);
        $rl = new RenderListe($listeTouite);
        $html = $rl->render(1);
        $html .= "<br><h3>Statiques utilisateur</h3>".$user->utilisateurNarcissique();
        return $html;
    }
}