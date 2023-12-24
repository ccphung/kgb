<?php
require_once('app/Controller.php');

class Agents extends Controller {
    protected $Agent;

    public function index() {

        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $this->loadModel("Agent");
        $title = "Agents";
        $paginationInfo = $this->Agent->pagination();
        $agents = $this->Agent->getAllAgents();

        $this->render('index', [
            'agents' => $agents,
            'pagination' => $paginationInfo,
            'title' => $title,
            'currentPage' => $currentPage
        ]);

    }
}