<?php
require_once('app/Controller.php');

class Contacts extends Controller {
    protected $Contact;

    public function index() {

        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $this->loadModel("Contact");
        $title = "Contacts";
        $paginationInfo = $this->Contact->pagination();
        $contacts = $this->Contact->getAllContacts();

        $this->render('index', 
        ['contacts' => $contacts, 
        'title' => $title,
        'currentPage' => $currentPage,
        'pagination' => $paginationInfo,
    ]);

    }
}