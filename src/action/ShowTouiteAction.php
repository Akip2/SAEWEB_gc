<?php
namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\RenderTouite;

class ShowTouiteAction extends Action{
	public function execute() : string{
		$id = $_GET["id"];
        $t = new Touite($id);
        $render = new RenderTouite($t);
        $res = "  <h1>Touiter</h1>
        <H2>Touite de ".$t->auteur."</h2>.$render->render(2)";
        return $res;
	}
}