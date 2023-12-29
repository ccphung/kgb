<?php
require_once('app/Controller.php');
require_once('core/Form.php');
require_once('models/Agent.php');
require_once('models/Person.php');

class Agents extends Controller {
    protected $Agent;

    public function __construct()
    {
        $this->loadModel("Agent");
    }

    public function index() {

        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

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

    public function processForm() {
        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

        //Check if form has been sent
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $firstName = $_POST["firstName"];
                $lastName = $_POST["lastName"];
                $birthDate = $_POST["birthDate"];
                $countryId = $_POST["country"];
                $agentCode = $_POST["agentCode"];
                $selectedSpecialties = $_POST["specialties"];

                $person = new Person();
                $person->firstName = $firstName;
                $person->lastName = $lastName;
                $person->birthDate = $birthDate;
                $person->nationality = $countryId;

                $person->insertPerson();

                $personId = $person->getLastId();
    
                $agent = new Agent();
                $agent->personId = $personId;
                $agent->agentCode = $agentCode;
    
            // Insère le nouvel agent dans la base de données
                $agent->insertAgent();
    
            // Insère les spécialités de l'agent
            foreach ($selectedSpecialties as $specialtyId) {
                
            }
                header("Location: ");
                exit();
            }
        
    }}

            public function createForm() {
                $countries = $this->Agent->getCountries();
                $specialties = $this->Agent->getSpecialties();
                $title = "Ajout agent";

                //Check if user is connected
                if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

                $form = new Form;

                $form->debutForm('POST', '/agents/post')
                    ->addLabelFor('firstName', 'Prénom :')
                    ->addInput('text', 'firstName', ['class' => 'form-control required'])

                    ->addLabelFor('lastName', 'Nom :')
                    ->addInput('text', 'lastName', ['class' => 'form-control'])

                    ->addLabelFor('birthDate', 'Date de naissance :')
                    ->addInput('date', 'birthDate', ['class' => 'form-control'])

                    ->addLabelFor('country', 'Nationalité :')
                    ->addSelect('country', array_column($countries, 'name', 'id'), ['id' => 'country', 'class' => 'form-control'])

                    ->addLabelFor('agentCode', 'Code agent :')
                    ->addInput('number', 'agentCode', ['class' => 'form-control'])

                    ->addLabelFor('specialty', 'Spécialité :');
                    foreach ($specialties as $key => $specialty) {
                        $specialtyName = $specialty['name'];
                    
                        $form->addInput('checkbox', 'specialties[]', ['class' => 'form-check-input mt-3 m-2'], $key)
                            ->addLabelFor($key, $specialtyName, ['class' => 'form-check-label mt-3 required']);
                    }

                    $form->addButton('Créer', ['class' => 'btn btn-primary mt-2 col-12'])
                    ->endForm();

                $this->render('add', ['countries' => $countries, 'title' => $title, 'addAgentForm' => $form->create()], );

           } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
           }
        }
    }    