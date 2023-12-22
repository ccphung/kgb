<?php
require_once('app/Controller.php');

class Missions extends Controller {
    protected $Mission;
    protected $MissionAgent;

    public function index() {
        $this->loadModel("Mission");
    
        $missions = $this->Mission->getAll();
        $title = "Missions";
        $this->render('index', ['missions' => $missions, 'title' => $title]);
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
}