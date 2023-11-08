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
        $res = "<div>".$this->touite->texte." 
        <a href=\"index.php?action=show_touite&id=".$this->touite->id_touite."\"> voir plus </a></div><br>";
        return $res;
    }

    public function long() :string  {
        $option = ""; 
        if (isset($_SESSION["user"])) {
            $option = "<a href=\"?action=noter&idTouite=".$this->touite->id_touite."\"> 
            <input type=\"button\" value=\"Noter\"> </a>";
        }
        $res = "<div>".$this->touite->nom_auteur." ".$this->touite->prenom_auteur.
        "<br>".$this->touite->texte.
        "<img src=\"".$this->touite->chemin_image."\"> </img> <br> 
        {$option}";
        //$this->touite->score."</div> <br>";
        return $res;
    }
}