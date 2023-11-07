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
        $res = "<div>".$this->touite->texte." <a href=\"index.php?action=show_touite&id=".$this->touite->id_touite."\"> voir plus </a></div><br>";
        return $res;
    }

    public function long() :string  {
        $res = "<div>".$this->touite->nom_auteur." ".$this->touite->prenom_auteur.
        "<br>".$this->touite->texte.
        "<img src=\"".$this->touite->chemin_image."\"> </img> <br> </div>";
        //$this->touite->score."</div> <br>";
        return $res;
    }
}