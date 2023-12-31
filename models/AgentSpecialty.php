<?php

class AgentSpecialty extends Model {
    public $agentId;
    public $specialtyId;

    public function __construct(){
        $this->table = "agent_specialty";
        $this->getConnexion();
    }

    public function insertAgentSpecialty() {
        $sql = "INSERT INTO agent_specialty (agent_id, specialty_id) VALUES (:agentId, :specialtyId)";
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':agentId', $this->agentId, PDO::PARAM_INT);
        $query->bindValue(':specialtyId', $this->specialtyId, PDO::PARAM_INT);
        $query->execute();
    }

    public function getLastId(){
        return $this->_connexion->lastInsertId();
    }


}