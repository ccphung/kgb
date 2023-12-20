<?php
require_once('app/Controller.php');

class Missions extends Controller {
    protected $Mission;

    public function index() {
        $this->loadModel("Mission");
        $missions = $this->Mission->getAll();
        var_dump($missions);
        $this->render('index');
    }
}