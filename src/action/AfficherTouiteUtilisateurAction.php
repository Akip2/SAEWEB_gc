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
                $html = "<h2>Bienvenue sur votre profil ".$user->prenom." ".$user->nom."</h2>";
                $html .= $rl->render(1);
                $html .= Utilisateur::utilisateurNarcissique();     
            }
            else{
                $html = "<h2>Bienvenue sur le profil</h2><br>";
                $html .= $rl->render(1);
            }
        }
        else{
            $html = "<h2>Bienvenue sur le profil</h2><br>";
            $html .= $rl->render(1);
        }
        return $html;
    }
}