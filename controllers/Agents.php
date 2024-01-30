<?php
require_once('app/Controller.php');
require_once('core/Form.php');
require_once('models/Agent.php');
require_once('models/Person.php');
require_once('models/AgentSpecialty.php');

class Agents extends Controller {
    protected $Agent;
    protected $form;

    public function __construct()
    {
        $this->loadModel("Agent");
        $this->form = new Form;
    }
    
    public function index() {
        $title = "Agents";
        $paginationInfo = $this->Agent->pagination();
        $agents = $this->Agent->getAllAgents();

        $specialties = $this->Agent->getAgentSpecialties();

        $this->render('index', [
            'agents' => $agents,
            'pagination' => $paginationInfo,
            'title' => $title,
            'specialties' => $specialties,
        ]);
    }

    public function createForm() {
        $countries = $this->Agent->getCountries();
        $specialties = $this->Agent->getSpecialties();
        $title = "Ajout agent";

        //Check if user is connected
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
                $this->form->debutForm('POST', '/agents/post')
                    ->addLabelFor('firstName', 'Prénom :')
                    ->addInput('text', 'firstName', ['class' => 'form-control', 'required' => 'required'])
        
                    ->addLabelFor('lastName', 'Nom :')
                    ->addInput('text', 'lastName', ['class' => 'form-control', 'required' => 'required'])
        
                    ->addLabelFor('birthDate', 'Date de naissance :')
                    ->addInput('date', 'birthDate', ['class' => 'form-control', 'required' => 'required'])
        
                    ->addLabelFor('country', 'Nationalité :')
                    ->addSelect('country', array_column($countries, 'name', 'id'), ['id' => 'country', 'class' => 'form-control', 'required' => 'required'])
        
                    ->addLabelFor('agentCode', 'Code agent :')
                    ->addInput('number', 'agentCode', ['class' => 'form-control', 'required' => 'required'])
        
                    ->addInput('hidden', "csrf_token", [], $_SESSION['csrf_token'])
        
                    ->addLabelFor('specialty', 'Spécialité :');
                
                foreach ($specialties as $key => $specialty) {
                    $specialtyName = $specialty['name'];
                    $specialtyId = $specialty['id'];
                
                    $this->form->addInput('checkbox', 'specialties[]', ['class' => 'form-check-input mt-3 m-2'], $specialtyId)
                        ->addLabelFor($key, $specialtyName, ['class' => 'form-check-label mt-3']);
                }
        
                $this->form->addButton('Créer', ['class' => 'btn btn-primary mt-2 col-12'])
                    ->endForm();
        
                $this->render('add', ['countries' => $countries, 'title' => $title, 'addAgentForm' => $this->form->create()]); 

        } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
        }        
    }


    public function processForm() {
        //Check if form has been sent
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            var_dump($_SESSION['csrf_token']);
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error_message'] = "Une erreur est survenue";
                header('Location: /');
            } else {
                $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS);
                $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS);
                $birthDate = filter_input(INPUT_POST, 'birthDate', FILTER_SANITIZE_STRING );
                $countryId = filter_input(INPUT_POST, 'country', FILTER_VALIDATE_INT);
                $agentCode = filter_input(INPUT_POST, 'agentCode', FILTER_VALIDATE_INT);
                $selectedSpecialties = isset($_POST["specialties"]) ? $_POST["specialties"] : [];
                
                if (!preg_match('/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/', $firstName) || !preg_match('/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/', $lastName)) {
                    $_SESSION['error_message'] = "Le prénom et le nom ne doivent contenir que des lettres alphabétiques.";
                    header("Location: /agents/add");
                    exit();
                }

                if (!preg_match('/^[0-9]+$/', $agentCode)) {
                    $_SESSION['error_message'] = "Le code agent ne doit contenir que des chiffres.";
                    header("Location: /agents/add");
                    exit();
                }

            $formData = [
                'firstName' => $firstName,
                'lastName'=> $lastName,
                'birthDate' => $birthDate,
                'countryId' => $countryId,
                'agentCode' => $agentCode
            ];

            if ($this->form->areFieldsFilled($formData) && !empty($_POST["specialties"])) {

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
    
                $agent->insertAgent();

                foreach ($selectedSpecialties as $specialty) {
                    $agentSpecialty = new AgentSpecialty();
                    $agentSpecialty->agentId = $agent->getLastId();
                    $agentSpecialty->specialtyId = $specialty;
                    
                    $agentSpecialty->insertAgentSpecialty();
                }

                $_SESSION['success_message'] = "L'agent a bien été créé !";
                header("Location: /agents");
                exit();

            } else {
                $_SESSION['error_message'] = "Veuillez remplir tous les champs";
                header("Location: /agents/add");
                exit();
            }
        }
    }
    }   
}    