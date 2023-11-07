<?php 

namespace iutnc\touiter\render\Render;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\Renderer;

class RenderTouite implements Renderer {

    private string $nomListe;

    public function __construct(touite $t){
        $this->touite = $t; 
    }

    public function render(int $selector){
        return (($selector ===1) ? $this->compact() : $this->long());
    }

    public function compact() :string {
        $res = "<div>".$this->touite->text." <a href=\"lien vers le long\"> voir plus </a></div><br>";
        $res;
    }

    public function long() :string  {
        $res = "<div>".$this->touite->auteur."<br>".$this->touite->text.
        "<img src=\"".$this->touite->path."\"> </img> <br>".
        $this->touite->score."</div> <br>";
        return $res;
    }
}