<?php
require_once('app/Controller.php');
require_once('models/Person.php');

class Targets extends Controller {
    protected $Target;
    protected $form;

    public function __construct()
    {
        $this->loadModel("Target");
        $this->form = new Form;
    }

    public function index() {
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $title = "Cibles";
        $targets = $this->Target->getAllTargets();
        $paginationInfo = $this->Target->pagination();

        $this->render('index', 
        ['targets' => $targets, 
        'title' => $title,
        'currentPage' => $currentPage,
        'pagination' => $paginationInfo,]);
    }

    public function createForm() {
        $countries = $this->Target->getCountries();
        $title = "Ajout cible";

        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

            $this->form->debutForm('POST', '/targets/post')
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

            $this->render('add', ['countries' => $countries, 'title' => $title, 'addTargetForm' => $this->form->create()], );

        } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
        }
    }

    public function processForm() {
        //Check if form has been sent
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $firstName = $_POST["firstName"];
                $lastName = $_POST["lastName"];
                $birthDate = $_POST["birthDate"];
                $countryId = $_POST["country"];
                $codeName = $_POST["codeName"];

                $formData = [
                    'firstName' => $firstName,
                    'lastName'=> $lastName,
                    'brithDate' => $birthDate,
                    'countryId' => $countryId,
                    'codeName' => $codeName
                ];

                if ($this->form->areFieldsFilled($formData)) {
                    $person = new Person();
                    $person->firstName = $firstName;
                    $person->lastName = $lastName;
                    $person->birthDate = $birthDate;
                    $person->nationality = $countryId;

                    $person->insertPerson();

                    $personId = $person->getLastId();
        
                    $target = new Target();
                    $target->personId = $personId;
                    $target->codeName = $codeName;
        
                    $target->insertTarget();

                    $_SESSION['success_message'] = "La cible a bien été créée !";
                    header("Location: /targets");
                    exit();
            } else {
                $_SESSION['error_message'] = "Veuillez remplir tous les champs";
                header("Location: /targets/add");
                exit();
            }
        }
    }   

}