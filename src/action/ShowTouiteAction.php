<?php
namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\RenderTouite;

use iutnc\touiter\render\RenderMenu;

class ShowTouiteAction extends Action{
	public function execute() : string{

        $menu = RenderMenu::render();

		$id = $_GET["id"];
        $t = new Touite($id);
        $render = new RenderTouite($t);

        $res = "<div id='conteneur_principal'><div class='menu'>$menu</div>{$render->render(2)}</div>";
        return $res;
	}
}