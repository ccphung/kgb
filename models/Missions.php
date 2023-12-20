<?php
require_once('app/Model.php');

class Missions extends Model {
    public function __construct(){
        $this->table = "missions";
        $this->getConnection();
    }
}