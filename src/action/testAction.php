<?php
namespace iutnc\touiter\action;

use iutnc\touiter\auth\Auth;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\RenderTouite;

class testAction extends Action{
	public function execute() : string{
		$t = new Touite("Hello",date('Y-m-d'));
		$rt = new RenderTouite($t);
		echo $rt->render(2);
	}
}