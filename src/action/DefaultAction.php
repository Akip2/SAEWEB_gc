<?php
namespace iutnc\touiter\action;
use iutnc\touiter\action\Action;
class DefaultAction extends Action{
    public function execute() : string{
        return "  <h1>Touiter</h1>
        <H2>Bienvenue !</h2>
        <a href=\"index.php?action=inscription\">Ajout utilisateur</href><br>
        <a href=\"index.php?action=connexion\">Connexion</href><br>
        <a href=\"index.php?action=list_touite\">Lister les Touites</href><br>
        <a href=\"index.php?action=list_user_touite\">Touites d'une Personne</href><br>
        <a href=\"index.php?action=list_tag_touite\">Touites d'un tag</href><br>
        <a href=\"index.php?action=publish_touite\">Publier un touite</href><br>
        <a href=\"index.php?action=list_touite_utilisateur\">Afficher Touite Utilisateur</href><br>";

    }
}