<?php
namespace iutnc\touiter\action;
use iutnc\touiter\action\Action;
use iutnc\touiter\touite\Touite;
class DefaultAction extends Action{
    public function execute() : string{
        return "<h1>Touiter</h1>
        <h2>Bienvenue !</h2>
        <div class=\"menu\">
        <a href=\"index.php?action=inscription\">Ajout utilisateur</a><br>
        <a href=\"index.php?action=connexion\">Connexion</a><br>
        <a href=\"index.php?action=list_touite\">Lister les Touites</a><br>
        <a href=\"index.php?action=list_user_touite\">Touites d'une Personne</a><br>
        <a href=\"index.php?action=list_tag_touite\">Touites d'un tag</a><br>
        <a href=\"index.php?action=publish_touite\">Publier un touite</a><br>
        <a href=\"index.php?action=list_touite_utilisateur\">Afficher Touite Utilisateur</a><br>
        <a href=\"index.php?action=follow_tag\">Suivre des tags</a><br>
        </div>
        <div class=\"conteneur_touites\">".Touite::afficherTouiteAccueil()."</div>";

    }
}
