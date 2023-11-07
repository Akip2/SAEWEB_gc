<?php
namespace iutnc\touiter\action;
class NoterAction extends Action {
    public function execute() : string{
        if($this->http_method === "GET"){
            $note = Utilisateur::verifierAvis()
            if(){

            }
		}
		elseif($this->http_method === "POST"){
		}
		return $page;
    }
}