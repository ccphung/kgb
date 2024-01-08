<?php

require_once('models/Admin.php');
require_once('models/mission.php');
require_once('models/Agent.php');
require_once('models/AgentMission.php');
require_once('models/ContactMission.php');
require_once('models/TargetMission.php');
require_once('models/AgentSpecialty.php');
require_once('models/AgentSpecialty.php');

class Admins extends Controller {

    private $mission;
    private $agent;
    private $MissionForm;
    private $buttonMissionForm;
    private $buttonAgentForm;
    private $AgentForm;
    private $person;

    public function __construct()
    {
        $this->mission = new Mission();
        $this->agent = new Agent();
        $this->person = new Person();
        $this->MissionForm = new Form();
        $this->buttonMissionForm = new Form();
        $this->buttonAgentForm = new Form();
        $this->AgentForm = new Form();
    }

    public function index() 
    {
        if($this->isAdmin()){

            $title ="Espace admin";
            $this->render('index',[
                'title' => $title]);
        }
    }

    public function getMissionId(){
        $currentUri = $_SERVER['REQUEST_URI'];
        preg_match('/\/post\/(\d+)$/', $currentUri, $matches);
    
        if (isset($matches[1])) {
            $_SESSION['missionId'] = $matches[1];
        }
    }

    public function getMissionIdforDelete(){
        $currentUri = $_SERVER['REQUEST_URI'];
        preg_match('/\/delete\/(\d+)$/', $currentUri, $matches);
    
        if (isset($matches[1])) {
            $_SESSION['mission_id'] = $matches[1];
        }
    }

    public function missions()
    {
        if($this->isAdmin()){
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $missionList = $this->mission->getAllmissions();
        $paginationInfo = $this->mission->pagination();
        $title = "Modification missions";

        $this->render('missions', [
            'missions' => $missionList,
            'pagination' => $paginationInfo,
            'currentPage' => $currentPage,
            'title' => $title
        ]);
        }
    }

    public function createMissionForm($id) {
        $title = "Modification mission";
        $missionDatas = $this->mission->getDataForMission($id);
        $contactFormission = $this->mission->getContactForMission($id);

        $countries = $this->mission->getCountries();
        $countriesOptions = ['' => 'Choisir le pays'] + array_column($countries, 'name', 'id');
        $specialties = $this->mission->getSpecialties();
        $specialtiesOptions = [ '' => 'Choisir une spécialité' ] + array_column($specialties, 'name', 'id');
        $status = $this->mission->getStatus();
        $types = $this->mission->getTypes();

        //Retrieve agents checked
        $agentsIdsArray = [];
        $agentsIds = $this->mission->getAgentsForMission($id);
        foreach($agentsIds as $key => $value){
             array_push($agentsIdsArray, $value['agent_id']);
        }

        //Retrieve targets checked
        $targetIdsArray = [];
        $targetsIds = $this->mission->getTargetForMission($id);
        foreach($targetsIds as $key => $value){
            array_push($targetIdsArray, $value['id']);
       }

       //Retrieve contacts checked
       $contactIdsArray = [];
       $contactIds = $this->mission->getContactForMission($id);
       foreach($contactIds as $key => $value){
           array_push($contactIdsArray, $value['id']);
      }

        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            foreach($missionDatas as $key => $value) {
                $agents = $this->generateStaticAgentsList($value['rs_id'], $agentsIdsArray);
                $countries = $this->generateStaticStakeoutAndContactsList($value['country_id'], $contactIdsArray);
                $targets = $this->generateStaticTargetList($agentsIds, $targetIdsArray);
            }
                $this->MissionForm->debutForm('POST', '/admin/mission/post/'.$id, ['id' => 'filters', 'class' => 'col-md-8 col-sm-12'])
                    ->debutFieldSet(['class' => 'bg-dark p-3 m-2'], 'Informations générales')
                    ->addLabelFor('title', 'Titre :',['class' => 'col-3 mx-2'])
                    ->addInput('text', 'title', ['class' => 'col-8 mx-2'], htmlspecialchars($value['title'], ENT_QUOTES, 'UTF-8'))

                    ->addLabelFor('code_name', 'Nom de code :', ['class' => 'col-3 mx-2'])
                    ->addInput('text', 'code_name', ['class' => 'col-8 m-2'], htmlspecialchars($value['mission_codeName'], ENT_QUOTES, 'UTF-8'))

                    ->addLabelFor('description', 'Description :',['class' => 'col-12 mx-2'])
                    ->addTextArea('description', htmlspecialchars($value['description'], ENT_QUOTES, 'UTF-8'), ['class' => 'col-12'] )

                    ->addLabelFor('country', 'Pays :', ['class' => 'col-5 mx-2'])
                    ->addSelect('country', $countriesOptions, ['id' => 'country', 'class' => 'col-5'], $value['country_id'])

                    ->addLabelFor('specialty', 'Spécialité requise :',['class' => 'mx-2 col-5'])
                    ->addSelect('specialty', $specialtiesOptions, ['id' => 'specialty', 'class' => 'col-5 specialty'], $value['rs_id'])
                ->endFieldset()

                ->debutFieldSet(['class' => 'bg-dark p-3 m-2'], 'Détails')
                    ->addLabelFor('type', 'Type :', ['class' => 'col-5 mx-2'])
                    ->addSelect('type', array_column($types, 'name', 'id'), ['id' => 'type', 'class' => 'col-5'], $value['type_id'])

                    ->addLabelFor('status', 'Statut :',['class' => 'col-5 mx-2'])
                    ->addSelect('status', array_column($status, 'name', 'id'), ['id' => 'status', 'class' => 'col-5'], $value['status_id'])

                    ->addLabelFor('start_date', 'Date de début :',['class' => 'col-5 mx-2 mt-2'])
                    ->addInput('date', 'start_date', ['class' => 'col-5'], $value['start_date'])

                    ->addLabelFor('end_date', 'Date de fin :',['class' => 'col-5 mx-2'])
                    ->addInput('date', 'end_date', ['class' => 'col-5'], $value['end_date'])

                ->endFieldset()

                ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'local'], '', $countries)
                    
                ->endFieldset()

                ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'agent'], ' ', $agents)
                ->endFieldset()

                ->debutFieldSet(['class' => 'bg-dark p-3 m-2', 'id' => 'target'], '', $targets)
                ->endFieldset()

                 ->addButton('Enregistrer', ['class' => 'btn btn-primary mt-2 col-12'])
                ->endForm();

                $this->buttonMissionForm->debutForm('POST', '/admin/mission/delete/'.$id, ['class' => 'col-md-8 col-sm-12'])
                ->addButton('Supprimer', ['class' => 'btn btn-danger mt-2 col-12'])
                ->endForm();

                $this->render('modifyMission', ['countries' => $countries, 'modifyForm' => $this->MissionForm->create(), 'title' => $title, 'contact' => $contactFormission, 'agents' => $agents, 'buttonForm' => $this->buttonMissionForm->create()]);

            } else {
                $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
                header('Location: /login');
                exit();
            }
        }


    /**
     * Check if admin is connected
     * @return true
     */
    private function isAdmin()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {
            return true;
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
    
    /**
     * Generate agents list
     * @return string
     */
    private function generateAgentsList($specialtyId) {
        $agentsSpecialty = $this->mission->getAgentsSpecialty($specialtyId);
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

    /**
     * Generate checked agents
     * @return string
     */
    private function generateStaticAgentsList($specialtyId, $agentsIds) {
        $agentsSpecialty = $this->mission->getAgentsSpecialty($specialtyId);
        $codeDisplay = '';

        $codeDisplay .= "<legend>Agents qualifiés</legend>";
        $codeDisplay .= "<label for='agents' id='agents' class='col-12'>Liste des agents :</label>";

        foreach ($agentsSpecialty as $value) {
            $id = $value['id'];
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);
            $name = ucfirst($value['name']);

            if(in_array($id, $agentsIds)) {
                $codeDisplay .= "<input class='col-6 agents' type='checkbox' id=\"{$value['id']}\" name='agents[]' value=\"{$value['id']}\" checked> {$firstName}  {$lastName} ({$name})</input>";
            } else {
                $codeDisplay .= "<input class='col-6 agents' type='checkbox' id=\"{$value['id']}\" name='agents[]' value=\"{$value['id']}\"> {$firstName}  {$lastName} ({$name})</input>";
            }
        }

        return $codeDisplay;
    }
    
    /**
     * Generate contacts and stakeouts
     * @return string
     */
    private function generateStakeoutAndContactsList($countryId) {
        $contacts = $this->mission->getContactCountry($countryId);
        $stakeoutCountry = $this->mission->getStakeoutCountry($countryId);
        
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
            $id = $value['contact_id'];
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);

            $codeDisplay .= "<input class='col-6 contacts' type='checkbox' id=\"{$value['contact_id']}\" name='contacts[]' value=\"{$value['contact_id']}\"> {$firstName}  {$lastName}</input>";
        }

        echo $codeDisplay;
    }

    /**
     * Generate checked contacts and selected stakeout
     * @return string
     */
    private function generateStaticStakeoutAndContactsList($countryId, $contactIds) {
        $contacts = $this->mission->getContactCountry($countryId);
        $stakeoutCountry = $this->mission->getStakeoutCountry($countryId);
        
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
            $id = $value['contact_id'];
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);

            if(in_array($id, $contactIds)) {
                $codeDisplay .= "<input class='col-6 contacts' type='checkbox' id=\"{$value['contact_id']}\" name='contacts[]' value=\"{$value['contact_id']}\" checked> {$firstName}  {$lastName}</input>";
            } else {
                $codeDisplay .= "<input class='col-6 contacts' type='checkbox' id=\"{$value['contact_id']}\" name='contacts[]' value=\"{$value['contact_id']}\"> {$firstName}  {$lastName}</input>";
            }
        }
        return $codeDisplay;
    }

    /**
     * Generate targets
     * @return string
     */
    public function generateTargetList() {
        $codeDisplay = '';

        $codeDisplay .= "<legend>Cibles</legend>";

        $codeDisplay .= "<label class='col-12'>Liste des cibles :</label>";

        $agentIds = $_POST['agentId'];

        $agentIdsFormatted = array_column($agentIds, "id");

        $agentCountry = $this->mission->getAgentCountry($agentIdsFormatted);

        $agentCountryIds = array_column($agentCountry, 'id');

        $targetList = $this->mission->getTargetCountry($agentCountryIds);

        foreach ($targetList as $key => $value) {
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);
            $name = ucfirst($value['name']);

            $codeDisplay .= "<input class='col-6' type='checkbox' id=\"{$value['id']}\" name='targets[]' value=\"{$value['id']}\"> {$firstName}  {$lastName} ({$name})</input>";
        }

        echo $codeDisplay;
    }

    /**
     * Generate checked targets
     * @return string
     */
    public function generateStaticTargetList($agentIds, $targetIds) {
        $codeDisplay = '';

        $codeDisplay .= "<legend>Cibles</legend>";

        $codeDisplay .= "<label class='col-12'>Liste des cibles :</label>";

        $agentIdsFormatted = array_column($agentIds, "agent_id");

        $agentCountry = $this->mission->getAgentCountry($agentIdsFormatted);

        $agentCountryIds = array_column($agentCountry, 'id');

        $targetList = $this->mission->getTargetCountry($agentCountryIds);

        foreach ($targetList as $key => $value) {
            $id = $value['id'];
            $firstName = ucfirst($value['first_name']);
            $lastName = ucfirst($value['last_name']);
            $name = ucfirst($value['name']);

            if(in_array($id, $targetIds)) {
            $codeDisplay .= "<input class='col-6' type='checkbox' id=\"{$value['id']}\" name='targets[]' value=\"{$value['id']}\" checked> {$firstName}  {$lastName} ({$name})</input>";
            } else {
                $codeDisplay .= "<input class='col-6' type='checkbox' id=\"{$value['id']}\" name='targets[]' value=\"{$value['id']}\"> {$firstName}  {$lastName} ({$name})</input>";
            }
        }


        return $codeDisplay;
    }

    
    public function processMissionForm() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                $this->getMissionId();
                $missionId = $_SESSION['missionId'];
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

                if(isset($_POST['agents'])) {
                    $agents = $_POST['agents'];
                } else {
                    $agents = [];
                }
                if(isset($_POST['contacts'])) {
                    $contacts = $_POST['contacts'];
                } else {
                    $contacts = [];
                }            
                if(isset($_POST['targets'])) {
                    $targets = $_POST['targets'];
                } else {
                    $targets = [];
                }
                        
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

                if ($this->MissionForm->areFieldsFilled($formData) && !empty($_POST['contacts']) && !empty($_POST['agents']) && !empty($_POST['targets'])) {
                        $this->mission->title = $title;
                        $this->mission->description = $description;
                        $this->mission->codeName = $codeName;
                        $this->mission->startDate = $startDate;
                        $this->mission->endDate = $endDate;
                        $this->mission->type = $type;
                        $this->mission->status = $status;
                        $this->mission->specialty = $specialty;
                        $this->mission->stakeout = $stakeout;
                        $this->mission->country = $country;
                        $this->mission->id = $missionId;
    
                        $this->mission->updateMission();
                        $this->mission->deleteAgentMission();
                        $this->mission->deleteTargetMission();
                        $this->mission->deleteContactMission();

                    foreach ($agents as $agent) {
                        $agentMission = new AgentMission();
                        $agentMission->missionId = $missionId;
                        $agentMission->agentId = $agent;
                        
                        $agentMission->insertAgentMission();
                    }

                    foreach ($contacts as $contact) {
                        $contactMission = new ContactMission();
                        $contactMission->missionId = $missionId;
                        $contactMission->contactId = $contact;
                        
                        $contactMission->insertContactMission();
                    }

                    foreach ($targets as $target) {
                        $targetMission = new TargetMission();
                        $targetMission->missionId = $missionId;
                        $targetMission->targetId = $target;
                        
                        $targetMission->insertTargetMission();
                    }
                
                    $_SESSION['success_message'] = "La mission a bien été modifiée !";
                    header("Location: /admin/missions");
                    exit();
            } else {
                echo "Veuillez remplir tous les champs. Cliquer sur précédent pour revenir sur le formulaire";
            }
        }
    }

    public function processMissionDelete()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->getMissionIdforDelete();
                $missionId = $_SESSION['mission_id'];
                $this->mission->id = $missionId;

                $this->mission->deleteAgentMission();
                $this->mission->deleteTargetMission();
                $this->mission->deleteContactMission();
                $this->mission->deleteMission();

            unset($_SESSION['mission_id']);
            $_SESSION['success_message'] = "La mission a bien été supprimée";
            header("Location: /admin/missions");
            exit();
        }
    }

    public function agents()
    {
        if($this->isAdmin()){
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $agentList = $this->agent->getAllAgents();
        $paginationInfo = $this->mission->pagination();
        $title = "Modification missions";
        $specialties = $this->agent->getAgentSpecialties();

        $this->render('agents', [
            'agents' => $agentList,
            'pagination' => $paginationInfo,
            'currentPage' => $currentPage,
            'title' => $title,
            'specialties' => $specialties
        ]);
        }
    }

    public function createAgentForm($id)
    {
        if($this->isAdmin()){
            $agents = $this->agent->getAgentInfo($id);
            foreach($agents as $key => $value){
                $agentFirstName = $value['first_name'];
                $agentLastName = $value['last_name'];
                $agentBirthDate = $value['birth_date'];
                $agentCode = $value['agent_code'];
                $agentCountry = $value['country_id'];
            }
            $specialtyIdsArray = [];
            foreach($agents as $agent) {
                array_push($specialtyIdsArray, $agent['specialties']);
            }

            $countries = $this->agent->getCountries();
            $specialties = $this->agent->getSpecialties();
            $title = "Modification agent";

            $this->AgentForm->debutForm('POST', '/admin/agent/post/'.$id)
            ->addLabelFor('firstName', 'Prénom :')
            ->addInput('text', 'firstName', ['class' => 'form-control', 'required' => 'required'],   ucfirst($agentFirstName))

            ->addLabelFor('lastName', 'Nom :')
            ->addInput('text', 'lastName', ['class' => 'form-control', 'required' => 'required'], ucfirst($agentLastName))

            ->addLabelFor('birthDate', 'Date de naissance :')
            ->addInput('date', 'birthDate', ['class' => 'form-control', 'required' => 'required'], $agentBirthDate)

            ->addLabelFor('country', 'Nationalité :')
            ->addSelect('country', array_column($countries, 'name', 'id'), 
                ['id' => 'country', 
                'class' => 'form-control', 
                'required' => 'required',
                ],$agentCountry)

            ->addLabelFor('agentCode', 'Code agent :')
            ->addInput('number', 'agentCode', ['class' => 'form-control', 'required' => 'required'], $agentCode)

            ->addLabelFor('specialty', 'Spécialité :');
            foreach ($specialties as $key => $specialty) {
                $specialtyName = $specialty['name'];
                $specialtyId = $specialty['id'];
            
                if(in_array($specialtyId, $specialtyIdsArray )){
                    $this->AgentForm->addInput('checkbox', 'specialties[]', ['class' => 'form-check-input mt-3 m-2', 'checked' => 'checked'], $specialtyId)
                    ->addLabelFor($key, $specialtyName, ['class' => 'form-check-label mt-3']);
                } else {
                    $this->AgentForm->addInput('checkbox', 'specialties[]', ['class' => 'form-check-input mt-3 m-2'], $specialtyId)
                    ->addLabelFor($key, $specialtyName, ['class' => 'form-check-label mt-3']);
                }
            }

            $this->AgentForm->addButton('Enregistrer', ['class' => 'btn btn-primary mt-2 col-12'])
            ->endForm();

            
            $this->buttonAgentForm->debutForm('POST', '/admin/agent/delete/'.$id)
            ->addButton('Supprimer', ['class' => 'btn btn-danger mt-2 col-12'])
            ->endForm();

             $this->render('modifyAgent', ['countries' => $countries, 'title' => $title, 'modifyForm' => $this->AgentForm->create(), 'deleteButton' => $this->buttonAgentForm->create()] );

        } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
        }
    }

    public function getAgentId(){
        $currentUri = $_SERVER['REQUEST_URI'];
        preg_match('/\/modify\/(\d+)$/', $currentUri, $matches);
    
        if (isset($matches[1])) {
            $_SESSION['agentId'] = $matches[1];
        }
    }
    
    public function processAgentForm($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->getAgentId();
            $agentCode = $_POST["agentCode"];
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $birthDate = $_POST["birthDate"];
            $specialties = $_POST["specialties"];
            $country = $_POST["country"];
            $agents = $this->agent->getAgentInfo($id);
            $agentId = $_SESSION['agentId'];
            var_dump($country);

            foreach($agents as $key => $value){
                $agentId = $value['id'];
            }

            $formData = [
                'agentCode' => $agentCode,
            ];

            if ($this->AgentForm->areFieldsFilled($formData) && !empty($_POST['specialties'])) {

                $this->agent->agentCode = $agentCode;
                $this->agent->id = $agentId;
                $this->agent->updateAgent();
                $this->agent->deleteAgentSpecialty();

                foreach ($specialties as $specialty) {
                    $agentSpecialty = new AgentSpecialty();
                    $agentSpecialty->agentId = $agentId;
                    $agentSpecialty->specialtyId = $specialty;
                    
                    $agentSpecialty->insertAgentSpecialty();
                }

                $this->person->firstName = $firstName;
                $this->person->lastName = $lastName;
                $this->person->birthDate = $birthDate;
                $this->person->id = $agentId;
                $this->person->country = $country;

                $this->person->updateAgentPerson();


                
                $_SESSION['success_message'] = "L'agent a bien été modifié !";
                header("Location: /admin/agents");
                exit();

            } else {
                echo "Veuillez remplir tous les champs. Cliquer sur précédent pour revenir sur le formulaire";
            }
        }
    }

    public function getAgentIdForDelete(){
        $currentUri = $_SERVER['REQUEST_URI'];
        preg_match('/\/delete\/(\d+)$/', $currentUri, $matches);
    
        if (isset($matches[1])) {
            $_SESSION['agent_id'] = $matches[1];
        }
    }

    public function processAgentDelete()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $this->getAgentIdForDelete();
                $agentId = $_SESSION['agent_id'];

                $this->agent->id = $agentId;
                $agents = $this->agent->getAgentInfo($agentId);

                foreach($agents as $key => $value){
                    $personId = $value['person_id'];
                }

                $this->person->personId = $personId;

                $this->agent->deleteAgentSpecialty();
                $this->person->deletePerson();
                $this->agent->deleteAgent();

            $_SESSION['success_message'] = "L'agent a bien été supprimée";
            header("Location: /admin/agents");
            exit();
        }
    }
}

