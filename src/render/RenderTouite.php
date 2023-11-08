<?php 

namespace iutnc\touiter\render;
use iutnc\touiter\touite\Touite;
use iutnc\touiter\render\Renderer;
use iutnc\touiter\utilisateur\Utilisateur;

class RenderTouite implements Renderer {

    private Touite $touite;

    public function __construct(touite $t){
        $this->touite = $t; 
    }

    public function render(int $selector) : string{
        return (($selector ===1) ? $this->compact() : $this->long());
    }

    public function compact() :string {
        $res = "<p class='touite'>".$this->TexttoTag($this->touite->texte)." 
        <a href=\"index.php?action=show_touite&id=".$this->touite->id_touite."\"> voir plus </a></p><br>";
        return $res;
    }

    public function TexttoTag(string $texte) : string {
        $tags = Touite::getTags($texte);
        $txt = $this->touite->texte;
        foreach($tags as $index => $value) {
            $id = Touite::getTagId($value);
            $taglien[$index] = "<a href=\"index.php?action=list_touite_tag&id=".$id."\">
            ".$value." </a>";
        }
        $mots = explode(" ",$txt);
        foreach($mots as $indexMots => $valueMots) {
            foreach($tags as $indexTags => $valueTags)  {
                if ($valueMots === $valueTags) {
                    $mots[$indexMots] = $taglien[$indexTags];
                }
            }
        }
        $txt = implode(" ",$mots);
        return $txt;
    }

    public function long() :string  {
        $touite = "";
        $noteTouite = $this->touite->noteTouite();
        $touite .="<p>Note :".$noteTouite."</p><br>";
        if (isset($_SESSION["user"])) {
            $u = unserialize($_SESSION["user"]);
            if ($this->touite->nom_auteur === $u->nom && $this->touite->prenom_auteur === $u->prenom){
                $touite .= "<a href=\"?action=sup_touite&idTouite=".$this->touite->id_touite."\"> 
                <input type=\"button\" value=\"Supprimer Touite\"> </a>";
            }else{

                if (Utilisateur::verifierSuivi($this->touite->id_auteur)){
                    $touite .= "<a href=\"?action=suivre&idUtilisateur=".$this->touite->id_auteur."&suivre=1\"> 
                    <input type=\"button\" value=\"Ne plus suivre\"> </a>";
                }else{
                    $touite .= "<a href=\"?action=suivre&idUtilisateur=".$this->touite->id_auteur."&suivre=0\"> 
                    <input type=\"button\" value=\"Suivre\"> </a>";
                }

            }
            $noteUtilisateur = Utilisateur::verifierAvis($this->touite->id_touite);
            if($noteUtilisateur !== 0){
                if($noteUtilisateur > 0){
                    $touite .= "<form id=\"noter\" method=\"POST\" action=\"?action=noter&idTouite={$this->touite->id_touite}\"/>
                    <input type='radio' name='choix' value='Dislike'> Dislike
                    <button type=\"submit\">Valider</button>
                    </form>";
                }else{
                    $touite .= "<form id=\"noter\" method=\"POST\" action=\"?action=noter&idTouite={$this->touite->id_touite}\"/>
                    <input type='radio' name='choix' value='Like'> Like
                    <button type=\"submit\">Valider</button>
                    </form>";

                }  
            }else{
                $touite .= "<form id=\"noter\" method=\"POST\" action=\"?action=noter&idTouite={$this->touite->id_touite}\"/>
                            <input type='radio' name='choix' value='Like'> Like
                            <input type='radio' name='choix' value='Dislike'> Dislike
                            <button type=\"submit\">Valider</button>
                            </form>";
            }
        }
        
        $res = "<p class='touie'>"."<a href=\"index.php?action=list_touite_utilisateur&id=".$this->touite->id_auteur."\">
        ".$this->touite->nom_auteur." ".$this->touite->prenom_auteur." </a><br>".
        "<br>".$this->TexttoTag($this->touite->texte).
        "<img src=\"".$this->touite->chemin_image."\"> </img> <br> 
        {$touite} </p>";
        return $res;
    }
}