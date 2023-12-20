<?php
require_once('app/Controller.php');

class MissionsController extends Controller {
    protected $Missions;

    public function index() {
        $this->loadModel("Missions");
        $missions = $this->Missions->getAll();
        var_dump($missions);
        require_once('views/missions.php');
    }
}