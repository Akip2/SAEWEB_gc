<?php

namespace iutnc\touiter\tag;
use Exception;

class Tag{
    private $libelle;
    private $description;

    public function __construct(string $lib, string $desc){
        $this->libelle = $lib;
        $this->description = $desc;
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