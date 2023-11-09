<?php
namespace iutnc\touiter\render;

class RenderMenu{
    static function render() :string{
        $u = unserialize($_SESSION["user"]);



        $option = "<br><a href=\"index.php?action=publish_touite\">Publier un touite</a>
        <a href=\"index.php?action=list_touite_utilisateur&id=$u->id\">Profil</a>
        <a href=\"index.php?action=follow_tag\">Suivre des tags</a>
        <a href=\"index.php?action=rechercher_tous_touites\">Voir tous les Touites</a><br>
        <a href=\"index.php?action=deconection\">Se déconnecter</a><br>";

        return $option;
    }
}