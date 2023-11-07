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
        session_start();
        switch($this->action){
            case "inscription":
                $action_class= new Action\InscriptionAction();
                break;
            case "connexion":
                $action_class= new Action\ConnexionAction();
                break;

            case "publish_touite":
                $action_class= new Action\PublishTouiteAction();
                break;

            case "noter":
                $action_class= new Action\NoterAction();
                break;

            default:
                $action_class = new Action\DefaultAction();
        }

        $html=$action_class->execute();

        $this->renderPage($html);
    }

    private function renderPage(string $html): void{
        $page = "<!doctype html>
        <html lang=\"fr\">
        <head>
            <meta charset=\"utf-8\">
            <title>Touiter</title>
            <link rel=\"stylesheet\" href=\"style.css\">
        </head></body>".$html."<br><br><a href=\"?home\"><button>Accueil</button></a></body></html>";
        print $page;
    }
}
