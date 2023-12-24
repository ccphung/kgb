<?php
require_once('app/Controller.php');

class Targets extends Controller {
    protected $Target;

    public function index() {
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $this->loadModel("Target");
        $title = "Cibles";
        $targets = $this->Target->getAllTargets();
        $paginationInfo = $this->Target->pagination();

        $this->render('index', 
        ['targets' => $targets, 
        'title' => $title,
        'currentPage' => $currentPage,
        'pagination' => $paginationInfo,]);
    }
}