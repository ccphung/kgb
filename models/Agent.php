<?php
require_once('app/Model.php');

class Agent extends Model {

    public $agentCode;
    public $personId;

    public function __construct(){
        $this->table = "agents";
        $this->getConnexion();
        $this->getCountries();
    }

    public function getAllAgents () {
        $paginationInfo = $this->pagination();
        
        $sql = "SELECT a.id, p.first_name, p.last_name, p.birth_date, c.name, a.agent_code
        FROM agents a
        JOIN persons p ON p.id = a.person_id
        JOIN countries c ON p.is_from = c.id
        ORDER BY a.agent_code ASC LIMIT :first, :perPage";

        $query = $this->_connexion->prepare($sql);

        $query->bindValue(':first', $paginationInfo['first'], PDO::PARAM_INT);
        $query->bindValue(':perPage', $paginationInfo['perPage'], PDO::PARAM_INT);
        
        $query->execute();

        $errorInfo = $query->errorInfo();
        if ($errorInfo[0] !== PDO::ERR_NONE) {
            die("Erreur PDO: " . $errorInfo[2]);
        }
        
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
            return $result;
    }

    public function insertAgent()
        {
            $this->getConnexion();

            $sql = "INSERT INTO agents (person_id, agent_code) VALUES (:personId, :agentCode)";
            $query = $this->_connexion->prepare($sql);
            $query->bindValue(':personId', $this->personId, PDO::PARAM_INT);
            $query->bindValue(':agentCode', $this->agentCode, PDO::PARAM_INT);
            
            $query->execute();

        }

    public function getAgentSpecialties() {
            $sql = "SELECT s.name, asp.agent_id
                    FROM specialties s
                    INNER JOIN agent_specialty asp ON s.id = asp.specialty_id";
                    
            $query = $this->_connexion->prepare($sql);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }

}