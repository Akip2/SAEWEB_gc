<?php
namespace iutnc\touiter\dispatch;

use \iutnc\touiter\action as Action;

//require_once 'vendor/autoload.php';


class Dispatcher{
    private string $action="";

    public function __construct(){
        if(isset($_GET["action"])){
            $this->action=$_GET["action"];
        }

    }

    public function run():void{
        switch($this->action){
            
        }

        $html=$action_class->execute();

        $this->renderPage($html);
    }

    private function renderPage(string $html): void{
        echo $html;
    }
}
