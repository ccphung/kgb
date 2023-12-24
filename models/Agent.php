<?php
require_once('app/Model.php');

class Agent extends Model {
    public function __construct(){
        $this->table = "agents";
        $this->getConnexion();

    }

    public function getAllAgents () {
        $paginationInfo = $this->pagination();
        
        $sql = "SELECT p.first_name, p.last_name, p.birth_date, c.name, a.agent_code
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
    }