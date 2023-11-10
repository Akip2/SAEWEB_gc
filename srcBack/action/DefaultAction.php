<?php
namespace iutnc\backOfficeTouiter\action;

class DefaultAction extends Action{
    public function execute() : string{
        $page = "";
        if (isset($_SESSION["user"])) {   //Si authentifié
            $u = unserialize($_SESSION["user"]);
            if($u->role === 100){
                $page = "<p>Actions disponibles :</p>\n<a href=\"?action=listerInfluenceurs\">Afficher utilisateurs influents</a>\n<br><a href=\"?action=listerTags\">Afficher les tags les plus utilisées</a>\n<br><a href=\"?action=deconnexion\">Deconnexion</a>\n";
            }
            else{
                $page = "<p>Vous n'avez pas accès.</p>";
            }
        }
        else{
            $page = "<a href=\"back_office.php?action=connexion\">Connexion</a>";    
        }
        return $page;
        
    }
}
