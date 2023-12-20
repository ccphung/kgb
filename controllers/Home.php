<?php
require_once('app/Controller.php');

class Home extends Controller {
    public function index() { 
        $this->render('index');

    }

}