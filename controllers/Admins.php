<?php

require_once('models/Admin.php');
require_once('models/Mission.php');
require_once('models/Agent.php');
require_once('models/AgentMission.php');
require_once('models/ContactMission.php');
require_once('models/TargetMission.php');
require_once('models/Contact.php');
require_once('models/Target.php');

class Admins extends Controller {

    private $mission;
    private $agent;
    private $missionForm;
    private $buttonMissionForm;
    private $buttonAgentForm;
    private $agentForm;
    private $person;
    private $contact;
    private $target;
    private $contactForm;
    private $targetForm;
    private $buttonTargetForm;
    private $buttonContactForm;


    public function __construct()
    {
        $this->mission = new Mission();
        $this->agent = new Agent();
        $this->person = new Person();
        $this->missionForm = new Form();
        $this->buttonMissionForm = new Form();
        $this->buttonAgentForm = new Form();
        $this->agentForm = new Form();
        $this->contact = new Contact();
        $this->target = new Target();
        $this->contactForm = new Form();
        $this->targetForm = new Form();
        $this->buttonContactForm = new Form();
        $this->buttonTargetForm = new Form();
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
                $this->missionForm->debutForm('POST', '/admin/mission/post/'.$id, ['id' => 'filters', 'class' => 'col-md-8 col-sm-12'])
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

                $this->render('modifyMission', ['countries' => $countries, 'modifyForm' => $this->missionForm->create(), 'title' => $title, 'contact' => $contactFormission, 'agents' => $agents, 'buttonForm' => $this->buttonMissionForm->create()]);

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

                if ($this->missionForm->areFieldsFilled($formData) && !empty($_POST['contacts']) && !empty($_POST['agents']) && !empty($_POST['targets'])) {
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
        $paginationInfo = $this->agent->pagination();
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

            $this->agentForm->debutForm('POST', '/admin/agent/post/'.$id)
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
                    $this->agentForm->addInput('checkbox', 'specialties[]', ['class' => 'form-check-input mt-3 m-2', 'checked' => 'checked'], $specialtyId)
                    ->addLabelFor($key, $specialtyName, ['class' => 'form-check-label mt-3']);
                } else {
                    $this->agentForm->addInput('checkbox', 'specialties[]', ['class' => 'form-check-input mt-3 m-2'], $specialtyId)
                    ->addLabelFor($key, $specialtyName, ['class' => 'form-check-label mt-3']);
                }
            }

            $this->agentForm->addButton('Enregistrer', ['class' => 'btn btn-primary mt-2 col-12'])
            ->endForm();

            
            $this->buttonAgentForm->debutForm('POST', '/admin/agent/delete/'.$id)
            ->addButton('Supprimer', ['class' => 'btn btn-danger mt-2 col-12'])
            ->endForm();

             $this->render('modifyAgent', ['countries' => $countries, 'title' => $title, 'modifyForm' => $this->agentForm->create(), 'deleteButton' => $this->buttonAgentForm->create()] );

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
            $agentCode = $_POST["agentCode"];
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $birthDate = $_POST["birthDate"];
            $specialties = $_POST["specialties"];
            $country = $_POST["country"];
            $agents = $this->agent->getAgentInfo($id);

            foreach($agents as $key => $value){
                $agentId = $value['id'];
            }

            $formData = [
                'agentCode' => $agentCode,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'birthDate' => $birthDate,
                'country' => $country,
            ];

            if ($this->agentForm->areFieldsFilled($formData) && !empty($_POST['specialties'])) {

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
                var_dump($agentId);

                $this->agent->id = $agentId;
                $this->agent->agentId = $agentId;
                $agents = $this->agent->getAgentInfo($agentId);

                foreach($agents as $key => $value){
                    $personId = $value['person_id'];
                }
                var_dump($personId);

                $this->person->personId = $personId;

                $this->agent->deleteAgent();

            $_SESSION['success_message'] = "L'agent a bien été supprimé";
            header("Location: /admin/agents");
            exit();
        }
    }

    public function contacts()
    {
        if($this->isAdmin()){
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $contacts = $this->contact->getAllContacts();
        $paginationInfo = $this->contact->pagination();
        $title = "Modification contact";

        $this->render('contacts', [
            'contacts' => $contacts,
            'pagination' => $paginationInfo,
            'currentPage' => $currentPage,
            'title' => $title,
        ]);
        }
    }

    public function targets()
    {
        if($this->isAdmin()){
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $targets = $this->target->getAllTargets();
        $paginationInfo = $this->target->pagination();
        $title = "Modification cibles";

        $this->render('targets', [
            'targets' => $targets,
            'pagination' => $paginationInfo,
            'currentPage' => $currentPage,
            'title' => $title,
        ]);
        }
    }

    public function createContactForm($id) {
        $countries = $this->contact->getCountries();
        $title = "Modification contact";

        $contacts = $this->contact->getContactInfo($id);

        foreach($contacts as $key => $value){
            $firstName = $value['first_name'];
            $lastName = $value['last_name'];
            $birthDate = $value['birth_date'];
            $country = $value['country_id'];
            $codeName = $value['code_name'];
        }

        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            $this->contactForm->debutForm('POST', '/admin/contact/post/' .$id)
                ->addLabelFor('firstName', 'Prénom :')
                ->addInput('text', 'firstName', ['class' => 'form-control required'], ucfirst($firstName))

                ->addLabelFor('lastName', 'Nom :')
                ->addInput('text', 'lastName', ['class' => 'form-control'], ucfirst($lastName))

                ->addLabelFor('birthDate', 'Date de naissance :')
                ->addInput('date', 'birthDate', ['class' => 'form-control'], $value['birth_date'])

                ->addLabelFor('country', 'Nationalité :')
                ->addSelect('country', array_column($countries, 'name', 'id'), ['id' => 'country', 'class' => 'form-control'], $country)

                ->addLabelFor('codeName', 'Nom de code :')
                ->addInput('text', 'codeName', ['class' => 'form-control'],$codeName)

                ->addButton('Enregistrer', ['class' => 'btn btn-primary mt-2 col-12'])
                ->endForm();

                $this->buttonContactForm->debutForm('POST', '/admin/contact/delete/'.$id)
                ->addButton('Supprimer', ['class' => 'btn btn-danger mt-2 col-12'])
                ->endForm();

                $this->render('modifyContact', ['countries' => $countries, 'title' => $title, 'modifyForm' => $this->contactForm->create(), 'deleteButton' => $this->buttonContactForm->create()]);

        } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
        }
    }

    public function createTargetForm($id) {
        $countries = $this->target->getCountries();
        $title = "Modification cible";

        $targets = $this->target->getTargetInfo($id);

        foreach($targets as $key => $value){
            $firstName = $value['first_name'];
            $lastName = $value['last_name'];
            $birthDate = $value['birth_date'];
            $country = $value['country_id'];
            $codeName = $value['code_name'];
        }

        //Check if user is connected
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){

            $this->targetForm->debutForm('POST', '/admin/target/post/' .$id)
                ->addLabelFor('firstName', 'Prénom :')
                ->addInput('text', 'firstName', ['class' => 'form-control required'],ucfirst($firstName))

                ->addLabelFor('lastName', 'Nom :')
                ->addInput('text', 'lastName', ['class' => 'form-control'],ucfirst($lastName))

                ->addLabelFor('birthDate', 'Date de naissance :')
                ->addInput('date', 'birthDate', ['class' => 'form-control'], $birthDate)

                ->addLabelFor('country', 'Nationalité :')
                ->addSelect('country', array_column($countries, 'name', 'id'), ['id' => 'country', 'class' => 'form-control'], $country)

                ->addLabelFor('codeName', 'Nom de code :')
                ->addInput('text', 'codeName', ['class' => 'form-control'], $codeName)

                ->addButton('Enregistrer', ['class' => 'btn btn-primary mt-2 col-12'])
                ->endForm();

                $this->buttonTargetForm->debutForm('POST', '/admin/target/delete/'.$id)
                ->addButton('Supprimer', ['class' => 'btn btn-danger mt-2 col-12'])
                ->endForm();

            $this->render('modifyTarget', ['countries' => $countries, 'title' => $title, 'modifyForm' => $this->targetForm->create(), 'deleteButton' => $this->buttonTargetForm->create()] );

        } else {
            $_SESSION['error_message'] = "Vous devez être connecté(e) pour accéder à cette page";
            header('Location: /login');
            exit();
        }
    }

    public function processContactForm($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $birthDate = $_POST["birthDate"];
            $country = $_POST["country"];
            $codeName = $_POST["codeName"];

            $contacts = $this->contact->getContactInfo($id);

            foreach($contacts as $key => $value){
                $contactId = $value['id'];
            }

            $formData = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'birthDate' => $birthDate,
                'country' => $country,
                'codeName' => $codeName
            ];

            if ($this->contactForm->areFieldsFilled($formData)) {

                $this->contact->codeName = $codeName;
                $this->contact->contactId = $contactId;

                $this->contact->updateContact();

                $this->person->firstName = $firstName;
                $this->person->lastName = $lastName;
                $this->person->birthDate = $birthDate;
                $this->person->contactId = $contactId;
                $this->person->country = $country;

                $this->person->updateContactPerson();
                
                $_SESSION['success_message'] = "Le contact a bien été modifié !";
                header("Location: /admin/contacts");
                exit();

            } else {
                echo "Veuillez remplir tous les champs. Cliquer sur précédent pour revenir sur le formulaire";
            }
        }
    }

    public function processTargetForm($id) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $birthDate = $_POST["birthDate"];
            $country = $_POST["country"];
            $codeName = $_POST["codeName"];

            $targets = $this->target->getTargetInfo($id);

            foreach($targets as $key => $value){
                $targetId = $value['id'];
            }

            $formData = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'birthDate' => $birthDate,
                'country' => $country,
                'codeName' => $codeName
            ];

            if ($this->targetForm->areFieldsFilled($formData)) {

                $this->target->codeName = $codeName;
                $this->target->targetId = $targetId;

                $this->target->updateTarget();

                $this->person->firstName = $firstName;
                $this->person->lastName = $lastName;
                $this->person->birthDate = $birthDate;
                $this->person->targetId = $targetId;
                $this->person->country = $country;

                $this->person->updateTargetPerson();
                
                $_SESSION['success_message'] = "La cible a bien été modifiée !";
                header("Location: /admin/targets");
                exit();

            } else {
                echo "Veuillez remplir tous les champs. Cliquer sur précédent pour revenir sur le formulaire";
            }
        }
    }

    public function processTargetDelete($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error_message'] = "Une erreur est survenue";
                header('Location: /');
            } else {
                $targets = $this->target->getTargetInfo($id);

                foreach($targets as $key => $value){
                    $targetId = $value['id'];
                    $personId = $value['person_id'];
                }
                    $this->target->targetId = $targetId;
                    $this->target->personId = $personId;
                    $this->target->deleteTarget();

                $_SESSION['success_message'] = "La cible a bien été supprimée";
                header("Location: /admin/targets");
                exit();
            }
        }
    }

    public function processContactDelete($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $contacts = $this->contact->getContactInfo($id);

            foreach($contacts as $key => $value){
                $contactId = $value['id'];
                $personId = $value['person_id'];
            }
                $this->contact->contactId = $contactId;
                $this->contact->personId = $personId;
                $this->contact->deleteContact();

            $_SESSION['success_message'] = "Le contact a bien été supprimé";
            header("Location: /admin/contacts");
            exit();
        }
    }

}

