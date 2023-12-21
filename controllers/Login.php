<?php
require_once('app/Controller.php');

class Login extends Controller {
    public function index() { 
        $title = "Connexion";
        $this->render('index', ['title' => $title]);
    }
}