<?php

namespace iutnc\touiter\utilisateur;
use Exception;

class Utilisateur{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $mail;
    private int $role;

    public function __construct(int $id, string $n, string $p, string $m, int $r){
        $this->id = $id;
        $this->nom = $n;
        $this->prenom = $p;
        $this->mail = $m;
        $this->role = $r;
    }

    public function __get( string $attr) : mixed {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new Exception("$attr : invalid property");
        }
    
    public function __set( string $attr, mixed $val) : void {
        if (property_exists($this, $attr)) $this->$attr = $val;
        throw new Exception("$attr : invalid property");
        }
}