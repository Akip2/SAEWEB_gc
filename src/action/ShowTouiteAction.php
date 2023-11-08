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
        $res = $render->render(2);
        return $res;
	}
}