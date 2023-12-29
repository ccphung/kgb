<?php
require_once('app/Controller.php');
require_once('models/Person.php');

class Contacts extends Controller {
    protected $Contact;

    public function __construct()
    {
        $this->loadModel("Contact");
    }

    public function index() {

        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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

    public function createForm() {
        $countries = $this->Contact->getCountries();
        $title = "Ajout contact";

        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

            $form = new Form;

            $form->debutForm('POST', '/contacts/post')
                ->addLabelFor('firstName', 'Prénom :')
                ->addInput('text', 'firstName', ['class' => 'form-control required'])

                ->addLabelFor('lastName', 'Nom :')
                ->addInput('text', 'lastName', ['class' => 'form-control'])

                ->addLabelFor('birthDate', 'Date de naissance :')
                ->addInput('date', 'birthDate', ['class' => 'form-control'])

                ->addLabelFor('country', 'Nationalité :')
                ->addSelect('country', array_column($countries, 'name', 'id'), ['id' => 'country', 'class' => 'form-control'])

                ->addLabelFor('codeName', 'Nom de code :')
                ->addInput('text', 'codeName', ['class' => 'form-control'])

                ->addButton('Créer', ['class' => 'btn btn-primary mt-2 col-12'])
                ->endForm();

            $this->render('add', ['countries' => $countries, 'title' => $title, 'addContactForm' => $form->create()], );

        } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
        }
    }

    public function processForm() {
        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

        //Check if form has been sent
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $firstName = $_POST["firstName"];
                $lastName = $_POST["lastName"];
                $birthDate = $_POST["birthDate"];
                $countryId = $_POST["country"];
                $agentCode = $_POST["codeName"];

                $person = new Person();
                $person->firstName = $firstName;
                $person->lastName = $lastName;
                $person->birthDate = $birthDate;
                $person->nationality = $countryId;

                $person->insertPerson();

                $personId = $person->getLastId();
    
                $contact = new Contact();
                $contact->personId = $personId;
                $contact->codeName = $agentCode;
    
                $contact->insertContact();

            $_SESSION['success_message'] = "Le contact a bien été créé !";
            header("Location: /contacts");
            exit();
            }
        }
    }   
}