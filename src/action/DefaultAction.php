<?php
namespace iutnc\touiter\action;
use iutnc\touiter\action\Action;
class DefaultAction extends Action{
    public function execute() : string{
        return "  <h1>Touiter</h1>
        <H2>Bienvenue !</h2>
        <a href=\"index.php?action=add-user\">Ajout utilisateur</href><br>
        <a href=\"index.php?action=sign\">Connexion</href><br>
        <a href=\"index.php?action=list_touite\">Lister les Touites</href><br>
        <a href=\"index.php?action=list_user_touite\">Touites d'une Personne</href><br>
        <a href=\"index.php?action=list_tag_touite\">Touites d'un tag</href><br>";

    }
}