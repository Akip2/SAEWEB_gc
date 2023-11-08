<?php 

namespace iutnc\touiter\render;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\Renderer;
use iutnc\touiter\render\RenderTouite;
use iutnc\touiter\touite\ListeTouite;

class RenderListe implements Renderer {

    private ListeTouite $touites;

    public function __construct(ListeTouite $t){
        $this->touites = $t; 
    }

    public function render(int $selector){
        return ($this->compact());
    }

    public function compact() {
        $res = $this->touites->name."<br> ";
        foreach($this->touites->liste as $value) {
            $render = new renderTouite($value);
            $res = $res.$render->render(1);
        }
        return $res;
    }
}