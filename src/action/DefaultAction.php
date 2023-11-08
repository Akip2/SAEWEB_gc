<?php
namespace iutnc\touiter\action;
use iutnc\touiter\action\Action;
use iutnc\touiter\touite\Touite;
class DefaultAction extends Action{
    public function execute() : string{
        $option = ""; 
        if (isset($_SESSION["user"])) {   //Si authentifié
            $option = "<a href=\"index.php?action=publish_touite\">Publier un touite</a><br>
            <a href=\"index.php?action=list_touite_utilisateur\">Afficher Touite Utilisateur</a><br>
            <a href=\"index.php?action=follow_tag\">Suivre des tags</a><br>
            <a href=\"index.php?action=deconection\">Se déconnecter</a><br>";
        }
        else{
            $option = "<a href=\"index.php?action=inscription\">Inscription</a><br>
                <a href=\"index.php?action=connexion\">Connexion</a><br>
                <a href=\"index.php?action=list_touite\">Lister les Touites</a><br>
                <a href=\"index.php?action=list_user_touite\">Touites d'une Personne</a><br>
                <a href=\"index.php?action=list_tag_touite\">Touites d'un tag</a><br>";
        }
        return "<h1>Touiter</h1>
        <h2>Bienvenue sur Touiter!</h2>
        <div id='conteneur_principal'>
        <div class=\"menu\">{$option}</div>
        <div class=\"conteneur_touites\">".Touite::afficherTouiteAccueil()."</div></div>";
        
    }
}
