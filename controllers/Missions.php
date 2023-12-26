<?php
require_once('app/Controller.php');

class Missions extends Controller {
    protected $Mission;
    protected $MissionAgent;

    public function index() {
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        $this->loadModel("Mission");
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
}