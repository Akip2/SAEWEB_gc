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
        $array = $this->touites->liste;
        $boutons = "";
        $nbtouites = sizeof($array);
        if($nbtouites > 10) {
            $nbpage = $nbtouites % 10 + 1;
            $page = New ListeTouite($this->touites->name);
            if (!isset($_GET["page"])) {
                $pvalue = 0;
            } else {
                $pvalue = $_GET["page"];
            }
            for ($i = $pvalue*10; $i<min(($pvalue+1)*10,$nbtouites); $i++) {
                $page->ajouterTouite($array[$i]);
            }
            $id = "";
            if(isset($_GET["id"])) {
                $id = "id=".$_GET["id"]."&";
            }
            if (isset($_GET["action"])) {
                $action = "action=".$_GET["action"]."&";
            }
            else{
                $action = "";
            }
            $pageprec = $pvalue -1;
            $pagesuiv = $pvalue +1;
            if ($pvalue > 0) {
                $boutons = "<a class=\"nomUtilisateurTouite\" href=\"index.php?".$action.$id."page=".$pageprec."\">  Page précédente </a>" ;
            } 
            if ($pvalue < $nbpage-2) {
                $boutons = $boutons."<a class=\"nomUtilisateurTouite\" href=\"index.php?".$action.$id."page=".$pagesuiv."\"> Page suivante </a>";
            }
        } else {
            $page = $this->touites;
        }
        $res = $this->touites->name."<br> ";
        foreach($page->liste as $value) {
            $render = new renderTouite($value);
            $res = $res.$render->render(1);
        }
        $res = $res.$boutons;
        return $res;
    }
}