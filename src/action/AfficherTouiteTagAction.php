<?php
namespace iutnc\touiter\action;
use iutnc\touiter\render\RenderListe;
use iutnc\touiter\render\RenderMenu;
use iutnc\touiter\touite\ListeTouite;
use iutnc\touiter\utilisateur\Utilisateur;

class AfficherTouiteTagAction extends Action{
	public function execute() : string{
        $menu;
        if(isset($_SESSION["user"])){
            $menu=RenderMenu::render();
        }
        else{
            $menu="<br><a href=\"index.php?action=inscription\">Inscription</a>
            <a href=\"index.php?action=connexion\">Connexion</a>";
        }


        $id = $_GET["id"];
        $user = new Utilisateur($id);
        $listeTouite = ListeTouite::listeTouiteTag($id);
        $rl = new RenderListe($listeTouite);
        $contenu="<div id='conteneur_principal'><div class='menu'>$menu</div><div class='conteneur_touites'>{$rl->render(1)}</div></div>";

        return $contenu;
    }
}