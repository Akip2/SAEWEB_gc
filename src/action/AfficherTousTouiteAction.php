<?php
namespace iutnc\touiter\action;
use iutnc\touiter\touite\Touite;

use iutnc\touiter\render\RenderMenu;

class AfficherTousTouiteAction extends Action{
	public function execute() : string{
        $menu=RenderMenu::render();
        $contenu="<div id='conteneur_principal'>
        <div class=\"menu\">{$menu}</div>
        <div class=\"conteneur_touites\">".Touite::afficherTouiteAccueil()."</div></div>
        </div>";

        return $contenu;
    }
}