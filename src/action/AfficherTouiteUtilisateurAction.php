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
        if(isset($_SESSION['user'])){
            $user = unserialize($_SESSION['user']);
            if($user->id === intval($id)){
                $html = "<h2>Bienvenue sur votre profil ".$user->prenom." ".$user->nom."</h2><div id=\"conteneur_principal\">";
                $html .= "<div class=\"conteneur_touites\">".$rl->render(1)."</div>";
                $html .= Utilisateur::utilisateurNarcissique()."\n</div>";     
            }
            else{
                $user = new Utilisateur(intval($id));
                $html = "<h2>Bienvenue sur le profil de ".$user->prenom." ".$user->nom."</h2><br><div id=\"conteneur_principal\">";
                $html .= "<div class=\"conteneur_touites\">".$rl->render(1)."</div>\n</div>";
            }
        }
        else{
            $user = new Utilisateur(intval($id));
            $html = "<h2>Bienvenue sur le profil de ".$user->prenom." ".$user->nom."</h2><br><div id=\"conteneur_principal\">";
            $html .= "<div class=\"conteneur_touites\">".$rl->render(1)."</div>\n</div>";
        }
        return $html;
    }
}