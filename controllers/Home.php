<?php
require_once('app/Controller.php');

class Home extends Controller {
    public function index() { 
        $title = "Accueil";
        $this->render('index', ['title' => $title]);
    }
}