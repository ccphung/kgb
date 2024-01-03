<?php
require_once('app/Model.php');

class TargetMission extends Model {
    public $targetId;
    public $missionId;

    public function __construct(){
        $this->table = "target_mission";
        $this->getConnexion();
    }

    public function insertTargetMission() {
        $sql = "INSERT INTO target_mission (target_id, mission_id) VALUES (:targetId, :missionId)";
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':targetId', $this->targetId, PDO::PARAM_INT);
        $query->bindValue(':missionId', $this->missionId, PDO::PARAM_INT);
        $query->execute();
    }
}