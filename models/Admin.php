<?php
require_once('app/Model.php');

class Admin extends Model {

    public function __construct(){
        $this->getConnexion();
    }
}