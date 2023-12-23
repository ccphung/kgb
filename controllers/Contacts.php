<?php
require_once('app/Controller.php');

class Contacts extends Controller {
    protected $Contact;

    public function index() {
        $this->loadModel("Contact");
        $title = "Contacts";
        $contacts = $this->Contact->getAllContacts();

        $this->render('index', ['contacts' => $contacts, 'title' => $title]);

    }
}