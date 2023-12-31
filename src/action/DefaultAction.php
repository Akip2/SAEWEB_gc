<?php
namespace iutnc\touiter\action;
use iutnc\touiter\action\Action;
use iutnc\touiter\touite\Touite;
class DefaultAction extends Action{
    public function execute() : string{
        $option = "<h2>Bienvenue sur Touiter!</h2>"; 
        if (isset($_SESSION["user"])) {   //Si authentifié
            $u = unserialize($_SESSION["user"]);
            $option .= "<br><a href=\"index.php?action=publish_touite\">Publier un touite</a>
            <a href=\"index.php?action=list_touite_utilisateur&id=$u->id\">Profil</a>
            <a href=\"index.php?action=follow_tag\">Suivre des tags</a>
            <a href=\"index.php?action=rechercher_tous_touites\">Voir tous les Touites</a><br>
            <a href=\"index.php?action=deconection\">Se déconnecter</a><br>";
            return "
            <div id='conteneur_principal'>
            <div class=\"menu\">{$option}</div>
            <div class=\"conteneur_touites\">".Touite::afficherMurAccueil()."</div></div>";
        }
        else{
            $option .= "<br><a href=\"index.php?action=inscription\">Inscription</a>
                <a href=\"index.php?action=connexion\">Connexion</a>";
                return
                "<div id='conteneur_principal'>
                <div class=\"menu\">{$option}</div>
                <div class=\"conteneur_touites\">".Touite::afficherTouiteAccueil()."</div></div>";
                
        }
        
        
    }
}
