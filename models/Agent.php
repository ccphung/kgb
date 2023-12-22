<?php
require_once('app/Model.php');

class Agent extends Model {
    public function __construct(){
        $this->table = "agents";
        $this->getConnexion();
    }

    public function getAllAgents () {
        $sql = "SELECT p.first_name, p.last_name, p.birth_date, c.name, a.agent_code
        FROM agents a
        JOIN persons p ON p.id = a.person_id
        JOIN countries c ON p.is_from = c.id";

        $query = $this->_connexion->prepare($sql);
        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }
}