<?php
require_once('app/Controller.php');

class Agents extends Controller {
    protected $Agent;

    public function index() {
        $this->loadModel("Agent");
        $title = "Agents";
        $agents = $this->Agent->getAllAgents();

        $this->render('index', ['agents' => $agents, 'title' => $title]);

    }
}