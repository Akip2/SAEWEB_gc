<?php
namespace iutnc\backOfficeTouiter\dispatch;

use \iutnc\backOfficeTouiter\action as Action;
use \iutnc\backOfficeTouiter\auth\Auth;



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
            case "connexion":
                $action_class= new Action\ConnexionAction();
                break;
            case "listerInfluenceurs":
                $action_class= new Action\ListerInfluenceursAction();
                break;
            case "listerTags":
                $action_class= new Action\ListerTagsAction();
                break;        
            case "deconnexion":
                Auth::disconnection();
                echo "<script>alert(\"Vous êtes déconnecté\");</script>\n";
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
        </head><body><h1 style=\"text-align:center;\">Back-Office Touiter</h1>".$html."<br><br>\n<a href=\"?home\"><button>Accueil</button></a>\n</body>\n</html>";
        print $page;
    }
}
