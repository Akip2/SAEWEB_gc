<?php
namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\touite\Touite;

class SupTouiteAction extends Action{
	public function execute() : string{
        Touite::supprimerTweet($_GET['idTouite']);
        return "Vous avez bien supprimé le touite";
	}
}