<?php
require_once('app/Controller.php');

class Login extends Controller {
    public function index() { 
        $this->render('index');
    }
}