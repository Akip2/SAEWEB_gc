<?php
namespace iutnc\touiter\action;
use iutnc\touiter\touite\Touite;

class AfficherTousTouiteAction extends Action{
	public function execute() : string{
        return "<div class=\"conteneur_touites\">".Touite::afficherTouiteAccueil()."</div></div>";
    }
}