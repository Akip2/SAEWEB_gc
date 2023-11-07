<?php 

namespace iutnc\touiter\render\Render;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\Renderer;

class RenderTouite implements Renderer {

    private ListeTouite $touites;

    public function __construct(touite $t){
        $this->touites = $t; 
    }

    public function render(int $selector){
        return ($this->compact());
    }

    public function compact() {
        $res = $this->touites->name."<br>"; 
        foreach($touites->liste as $value) {
            $render = new renderTouite($value);
            $res = $res.$render->render(1);
        }
        return $res;
    }
}