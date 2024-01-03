<?php
require_once('app/Controller.php');

class Missions extends Controller {
    protected $Mission;
    protected $MissionAgent;
    private $agentIds = [];

    public function __construct()
    {
        $this->loadModel("Mission");
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
        $this->loadModel("Mission");
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
        $stakeouts = $this->Mission->getStakeouts();
         
        $title = "Ajout mission";

        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

            $form = new Form;

            $form->debutForm('POST', '/mission/post', ['id' => 'filters'])
                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2'], 'Description')
                        ->addLabelFor('title', 'Titre :')
                        ->addInput('text', 'title', ['class' => 'col-md-4 m-2'])
                        ->addLabelFor('code_name', 'Nom de code :')
                        ->addInput('text', 'code_name', ['class' => 'col-4 m-2'])
                        ->addLabelFor('description', 'Description :',['class' => 'col-12'])
                        ->addTextArea('description', '', ['class' => 'col-12'])
                        ->addLabelFor('country', 'Pays :', ['class' => 'm-2'])
                        ->addSelect('country', $countriesOptions, ['id' => 'country', 'class' => 'col-12'])
                        ->addLabelFor('specialty', 'Spécialité requise :')
                        ->addSelect('specialty', $specialtiesOptions, ['id' => 'specialty', 'class' => 'col-12 specialty'])
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2'], 'Statut')
                        ->addLabelFor('type', 'Type :')
                        ->addSelect('type', array_column($types, 'name', 'id'), ['id' => 'type', 'class' => 'col-5 m-2'])
                        ->addLabelFor('status', 'Statut :')
                        ->addSelect('status', array_column($status, 'name', 'id'), ['id' => 'status', 'class' => 'col-4 m-2'])
                        ->addLabelFor('start_date', 'Date de début :')
                        ->addInput('date', 'start_date', ['class' => 'col-2 m-2'])
                        ->addLabelFor('end_date', 'Date de fin :')
                        ->addInput('date', 'end_date', ['class' => 'col-2 m-2'])
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'local'], 'Planque & Contacts locaux')
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'agent'], 'Agents qualifiés')
                    ->endFieldset()

                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'target'], 'Cibles')
                    ->endFieldset()

                ->addButton('Créer', ['class' => 'btn btn-primary mt-2 col-12'])
                ->endForm();

            $this->render('add', ['countries' => $countries, 'title' => $title, 'addMissionForm' => $form->create()], );

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
        $codeDisplay .= "<label for='agent' id='agents' class='col-12'>Liste des agents :</label>";

        foreach ($agentsSpecialty as $value) {
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);
            $name = ucfirst($value['name']);

            $codeDisplay .= "<input class='col-6 agents' type='checkbox' id=\"{$value['id']}\" name='agent[]'> {$firstName}  {$lastName} ({$name})</input>";
        }

        echo $codeDisplay;
    }
    
    private function generateStakeoutAndContactsList($countryId) {
        $contacts = $this->Mission->getContactCountry($countryId);
        $stakeoutCountry = $this->Mission->getStakeoutCountry($countryId);
        
        $codeDisplay = '';

        $codeDisplay .= "<legend>Planque & Contacts locaux</legend>";

        $codeDisplay .= "<label for='stakeout' class='col-12'>Planque :</label>";

        $codeDisplay .= "<select for='stakeout' class='col-12' name='stakeout'>";
 
        foreach ($stakeoutCountry as $value) {
            $codeDisplay .= "<option name='stakeout' value='{$value['id']}'>{$value['address']} - {$value['name']}</option>";
        }
        $codeDisplay .= "</select>";
    
        $codeDisplay .= "<label for='contact' class='col-12'>Contacts :</label>";
        foreach ($contacts as $value) {
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);

            $codeDisplay .= "<input class='col-6' type='checkbox' id=\"{$value['id']}\" name='contact[]'> {$firstName}  {$lastName}</input>";
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

            $codeDisplay .= "<input class='col-6' type='checkbox' id=\"{$value['id']}\" name='target[]'> {$firstName}  {$lastName} ({$name})</input>";
        }

        echo $codeDisplay;
    }
}
