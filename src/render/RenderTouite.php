<?php 

namespace iutnc\touiter\render;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\Renderer;

class RenderTouite implements Renderer {

    private Touite $touite;

    public function __construct(touite $t){
        $this->touite = $t; 
    }

    public function render(int $selector) : string{
        return (($selector ===1) ? $this->compact() : $this->long());
    }

    public function compact() :string {
        $res = "<p class='touite'>".$this->touite->texte." 
        <a href=\"index.php?action=show_touite&id=".$this->touite->id_touite."\"> voir plus </a></p><br>";
        return $res;
    }

    public function long() :string  {
        $note = ""; 
        if (isset($_SESSION["user"])) {
            $u = unserialize($_SESSION["user"]);
            if ($this->touite->nom_auteur === $u->nom && $this->touite->prenom_auteur === $u->prenom){
                $note = "<a href=\"?action=sup_touite&idTouite=".$this->touite->id_touite."\"> 
                <input type=\"button\" value=\"Supprimer Touite\"> </a>";
            }
            $note .= "<a href=\"?action=noter&idTouite=".$this->touite->id_touite."\"> 
            <input type=\"button\" value=\"Noter\"> </a>";
        }
        
        $res = "<p class='touie'>"."<a href=\"index.php?action=list_touite_utilisateur&id=".$this->touite->id_auteur."\">
        ".$this->touite->nom_auteur." ".$this->touite->prenom_auteur." </a><br>".
        "<br>".$this->touite->texte.
        "<img src=\"".$this->touite->chemin_image."\"> </img> <br> 
        {$note} </p>";
        return $res;
    }
}