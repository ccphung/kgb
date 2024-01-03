<?php
require_once('app/Model.php');

class AgentMission extends Model {
    public $agentId;
    public $missionId;

    public function __construct(){
        $this->table = "agent_mission";
        $this->getConnexion();
    }

    public function insertAgentMission() {
        $sql = "INSERT INTO agent_mission (agent_id, mission_id) VALUES (:agentId, :missionId)";
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':agentId', $this->agentId, PDO::PARAM_INT);
        $query->bindValue(':missionId', $this->missionId, PDO::PARAM_INT);
        $query->execute();
    }
}