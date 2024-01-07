<?php
require_once('app/Controller.php');
require_once('models/AgentMission.php');
require_once('models/ContactMission.php');
require_once('models/TargetMission.php');
require_once('core/Form.php');

class Missions extends Controller {
    protected $Mission;
    protected $MissionAgent;
    protected $form;

    public function __construct()
    {
        $this->loadModel("Mission");
        $this->form = new Form();
    }

    public function index() {
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $title = "Missions";
        $missions = $this->Mission->getAllMissions();
        $paginationInfo = $this->Mission->pagination();

        $this->render('index', [
        'missions' => $missions, 
        'title' => $title,
        'currentPage' => $currentPage,
        'pagination' => $paginationInfo,]);
    }

    public function details($id) {
        $this->Mission->id = $id;
        $mission = $this->Mission->getOne($id);
        $agentsForMission = $this->Mission->getAgentsForMission($id);
        $missionDatas = $this->Mission->getDataForMission($id);
        $targetsForMission = $this->Mission->getTargetForMission($id);
        $contactForMission = $this->Mission->getContactForMission($id);

        $title = "Detail mission";

        $this->render('detail', ['mission'=>$mission, 'agents' => $agentsForMission, 'title' => $title, 'datas'=>$missionDatas, 'targets' => $targetsForMission,
    'contacts' => $contactForMission]);
    }

    public function createForm() {
        $countries = $this->Mission->getCountries();
        $countriesOptions = ['' => 'Choisir le pays'] + array_column($countries, 'name', 'id');
        $specialties = $this->Mission->getSpecialties();
        $specialtiesOptions = [ '' => 'Choisir une spécialité' ] + array_column($specialties, 'name', 'id');
        $status = $this->Mission->getStatus();
        $types = $this->Mission->getTypes();
         
        $title = "Ajout mission";

        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            $this->form->debutForm('POST', '/mission/post', ['id' => 'filters', 'class' => 'col-md-8 col-sm-12'])
                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2'], 'Informations générales')
                        ->addLabelFor('title', 'Titre :',['class' => 'col-3 mx-2'])
                        ->addInput('text', 'title', ['class' => 'col-8 mx-2'])
                        ->addLabelFor('code_name', 'Nom de code :', ['class' => 'col-3 mx-2'])
                        ->addInput('text', 'code_name', ['class' => 'col-8 m-2'])
                        ->addLabelFor('description', 'Description :',['class' => 'col-12 mx-2'])
                        ->addTextArea('description', '', ['class' => 'col-12'])
                        ->addLabelFor('country', 'Pays :', ['class' => 'col-5 mx-2'])
                        ->addSelect('country', $countriesOptions, ['id' => 'country', 'class' => 'col-5'])
                        ->addLabelFor('specialty', 'Spécialité requise :',['class' => 'mx-2 col-5'])
                        ->addSelect('specialty', $specialtiesOptions, ['id' => 'specialty', 'class' => 'col-5 specialty'])
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2'], 'Détails')
                        ->addLabelFor('type', 'Type :', ['class' => 'col-5 mx-2'])
                        ->addSelect('type', array_column($types, 'name', 'id'), ['id' => 'type', 'class' => 'col-5'])
                        ->addLabelFor('status', 'Statut :',['class' => 'col-5 mx-2'])
                        ->addSelect('status', array_column($status, 'name', 'id'), ['id' => 'status', 'class' => 'col-5'])
                        ->addLabelFor('start_date', 'Date de début :',['class' => 'col-5 mx-2 mt-2'])
                        ->addInput('date', 'start_date', ['class' => 'col-5'])
                        ->addLabelFor('end_date', 'Date de fin :',['class' => 'col-5 mx-2'])
                        ->addInput('date', 'end_date', ['class' => 'col-5'])
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'local'], 'Planque & Contacts locaux')
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'agent'], 'Agents qualifiés')
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'target'], 'Cibles')
                    ->endFieldset()

                ->addButton('Créer', ['class' => 'btn btn-primary mt-2 col-12'])
                ->endForm();

            $this->render('add', ['countries' => $countries, 'title' => $title, 'addMissionForm' => $this->form->create()], );

        } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
        }
    }

    
    public function processSelection() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['specialtyId'])) {
                $this->generateAgentsList($_POST['specialtyId']);
            }
    
            if (isset($_POST['countryId'])) {
                $this->generateStakeoutAndContactsList($_POST['countryId']);
            }

            if (isset($_POST['agentId'])) {
                $this->generateTargetList($_POST['agentId']);
            }
        }
    }
    
    private function generateAgentsList($specialtyId) {
        $agentsSpecialty = $this->Mission->getAgentsSpecialty($specialtyId);
        $codeDisplay = '';

        $codeDisplay .= "<legend>Agents qualifiés</legend>";
        $codeDisplay .= "<label for='agents' id='agents' class='col-12'>Liste des agents :</label>";

        foreach ($agentsSpecialty as $value) {
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);
            $name = ucfirst($value['name']);

            $codeDisplay .= "<input class='col-6 agents' type='checkbox' id=\"{$value['id']}\" name='agents[]' value=\"{$value['id']}\"> {$firstName}  {$lastName} ({$name})</input>";
        }

        echo $codeDisplay;
    }
    
    private function generateStakeoutAndContactsList($countryId) {
        $contacts = $this->Mission->getContactCountry($countryId);
        $stakeoutCountry = $this->Mission->getStakeoutCountry($countryId);
        
        $codeDisplay = '';

        $codeDisplay .= "<legend>Planque & Contacts locaux</legend>";

        $codeDisplay .= "<label for='stakeout' class='col-3 mx-2'>Planque :</label>";

        $codeDisplay .= "<select for='stakeout' class='col-6 mx-2' name='stakeout'>";
 
        foreach ($stakeoutCountry as $value) {
            $codeDisplay .= "<option name='stakeout' value='{$value['id']}'>{$value['address']} - {$value['name']}</option>";
        }
        $codeDisplay .= "</select>";
    
        $codeDisplay .= "<label for='contact' class='col-12 mx-2'>Contacts :</label>";

        foreach ($contacts as $value) {
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);

            $codeDisplay .= "<input class='col-6 contacts' type='checkbox' id=\"{$value['contact_id']}\" name='contacts[]' value=\"{$value['contact_id']}\"> {$firstName}  {$lastName}</input>";
        }

        echo $codeDisplay;
    }

    public function generateTargetList() {
        $codeDisplay = '';

        $codeDisplay .= "<legend>Cibles</legend>";

        $codeDisplay .= "<label class='col-12'>Liste des cibles :</label>";

        $agentIds = $_POST['agentId'];

        $agentIdsFormatted = array_column($agentIds, "id");

        $agentCountry = $this->Mission->getAgentCountry($agentIdsFormatted);

        $agentCountryIds = array_column($agentCountry, 'id');

        $targetList = $this->Mission->getTargetCountry($agentCountryIds);

        foreach ($targetList as $key => $value) {
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);
            $name = ucfirst($value['name']);

            $codeDisplay .= "<input class='col-6' type='checkbox' id=\"{$value['id']}\" name='targets[]' value=\"{$value['id']}\"> {$firstName}  {$lastName} ({$name})</input>";
        }

        echo $codeDisplay;
    }

    public function processForm() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $codeName = $_POST["code_name"];
            $startDate = $_POST["start_date"];
            $endDate = $_POST["end_date"];
            $type = $_POST["type"];
            $status = $_POST["status"];
            $specialty = $_POST["specialty"];
            $stakeout = $_POST["stakeout"];
            $country = $_POST["country"];
            $agents = $_POST['agents'];
            $contacts = $_POST['contacts'];
            $targets = $_POST['targets'];
    
            
            $formData = [
                'title' => $title,
                'description' => $description,
                'codeName '=> $codeName,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'type' => $type,
                'status' => $status,
                'specialty' => $specialty,
                'stakeout' => $stakeout,
                'country' => $country,
            ];

            if ($this->form->areFieldsFilled($formData) && !empty($_POST['contacts']) && !empty($_POST['agents']) && !empty($_POST['targets'])) {
                $mission = new Mission();
                $mission->title = $title;
                $mission->description = $description;
                $mission->codeName = $codeName;
                $mission->startDate = $startDate;
                $mission->endDate = $endDate;
                $mission->type = $type;
                $mission->status = $status;
                $mission->specialty = $specialty;
                $mission->stakeout = $stakeout;
                $mission->country = $country;

                $mission->insertMission();

                foreach ($agents as $agent) {
                    $agentMission = new AgentMission();
                    $agentMission->missionId = $mission->getLastId();
                    $agentMission->agentId = $agent;
                    
                    $agentMission->insertAgentMission();
                }

                foreach ($contacts as $contact) {
                    $contactMission = new ContactMission();
                    $contactMission->missionId = $mission->getLastId();
                    $contactMission->contactId = $contact;
                    
                    $contactMission->insertContactMission();
                }

                foreach ($targets as $target) {
                    $targetMission = new TargetMission();
                    $targetMission->missionId = $mission->getLastId();
                    $targetMission->targetId = $target;
                    
                    $targetMission->insertTargetMission();
                }
                    
                $_SESSION['success_message'] = "La mission a bien été créée !";
                    header("Location: /missions");
                //     exit();

            } else {
                $_SESSION['error_message'] = "Veuillez remplir tous les champs";
                header("Location: /missions/add");
                exit();
            }
        }
    }
}
