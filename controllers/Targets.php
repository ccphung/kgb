<?php
require_once('app/Controller.php');

class Targets extends Controller {
    protected $Target;

    public function index() {
        $this->loadModel("Target");
        $title = "Cibles";
        $targets = $this->Target->getAllTargets();

        $this->render('index', ['targets' => $targets, 'title' => $title]);

    }
}