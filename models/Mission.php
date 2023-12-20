<?php
require_once('app/Model.php');

class Mission extends Model {
    public function __construct(){
        $this->table = "missions";
        $this->getConnection();
    }
}