<?php
require_once('app/Controller.php');

class Missions extends Controller {
    protected $Mission;

    public function index() {
        $this->loadModel("Mission");
        $missions = $this->Mission->getAll();
        $title = "Missions";
        $this->render('index', ['missions' => $missions, 'title' => $title]);
    }

    public function details($id) {
        $title = "test";
        echo $id;

    }
}