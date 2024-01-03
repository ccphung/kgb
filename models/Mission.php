<?php
require_once('app/Model.php');

class Mission extends Model {
    public $title;
    public $description;
    public $codeName;
    public $startDate;
    public $endDate;
    public $type;
    public $status;
    public $specialty;
    public $stakeout;
    public $country;

    public function __construct(){
        $this->table = "missions";
        $this->getConnexion();
    }

    public function getAllMissions(){
        $paginationInfo = $this->pagination();

        $sql = "SELECT m.code_name, m.title, s.name AS status, m.id
        FROM missions m
        JOIN status s ON s.id = m.mission_status LIMIT :first, :perPage";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':first', $paginationInfo['first'], PDO::PARAM_INT);
        $query->bindValue(':perPage', $paginationInfo['perPage'], PDO::PARAM_INT);

        $query->execute();

        $result = $query->fetchAll();

        return $result;
    }

    public function getAgentsForMission($missionId) {
        $sql = "SELECT p.first_name, p.last_name, a.agent_code
        FROM agent_mission am
        JOIN agents a ON am.agent_id = a.id
        JOIN persons p ON a.person_id = p.id
        WHERE am.mission_id = :mission_id";

    
        $query = $this->_connexion->prepare($sql);
        $query->bindParam(':mission_id', $missionId, PDO::PARAM_INT);
        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }
    
    public function getDataForMission($missionId) {
        $sql = "SELECT s.code, s.address, s.type, cs.name AS stakeout_country, cm.name AS mission_country, rs.name AS required_specialty, st.name AS status, t.name AS mission_type
        FROM missions m
        JOIN stakeouts s ON m.mission_stakeout = s.id
        JOIN countries cs ON s.is_located_in = cs.id
        JOIN status st ON st.id = m.mission_status
        JOIN types t ON m.mission_type = t.id
        JOIN countries cm ON m.takes_place_in = cm.id
        JOIN specialties rs ON m.required_specialty = rs.id
        WHERE m.id = :mission_id";
    
        $query = $this->_connexion->prepare($sql);
        $query->bindParam(':mission_id', $missionId, PDO::PARAM_INT);
        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getTargetForMission($missionId) {
        $sql = "SELECT p.first_name, p.last_name, t.code_name
        FROM target_mission tm
        JOIN targets t ON tm.target_id = t.id
        JOIN persons p ON t.person_id = p.id
        WHERE tm.mission_id = :mission_id";

    
        $query = $this->_connexion->prepare($sql);
        $query->bindParam(':mission_id', $missionId, PDO::PARAM_INT);
        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function getContactForMission($missionId) {
        $sql = "SELECT p.first_name, p.last_name, c.code_name
        FROM contact_mission cm
        JOIN contacts c ON cm.contact_id = c.id
        JOIN persons p ON c.person_id = p.id
        WHERE cm.mission_id = :mission_id";

    
        $query = $this->_connexion->prepare($sql);
        $query->bindParam(':mission_id', $missionId, PDO::PARAM_INT);
        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function getAgentsSpecialty($specialtyId) {
            $sql = "SELECT p.first_name, p.last_name, a.id, c.name
                    FROM persons p
                    JOIN agents a ON a.person_id = p.id
                    JOIN countries c ON p.is_from = c.id
                    JOIN agent_specialty asp ON asp.agent_id = a.id
                    WHERE asp.specialty_id = :specialty_id";
    
            $query = $this->_connexion->prepare($sql);
            $query->bindParam(':specialty_id', $specialtyId, PDO::PARAM_INT);
            $query->execute();
    
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function getStakeoutCountry($countryId) {
            $sql = "SELECT s.address, c.name, s.id
                    FROM stakeouts s
                    JOIN countries c ON s.is_located_in = c.id
                    WHERE c.id = :country_id";
    
            $query = $this->_connexion->prepare($sql);
            $query->bindParam(':country_id', $countryId, PDO::PARAM_INT);
            $query->execute();
    
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        
        public function getContactCountry($countryId) {
            $sql = "SELECT p.first_name, p.last_name, c.id
                    FROM persons p
                    JOIN contacts ct ON ct.person_id = p.id
                    JOIN countries c ON p.is_from = c.id
                    WHERE c.id = :country_id";

            $query = $this->_connexion->prepare($sql);
            $query->bindParam(':country_id', $countryId, PDO::PARAM_INT);
            $query->execute();
    
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function getAgentCountry($agentsIds) {
            $placeholders = rtrim(str_repeat('?, ', count($agentsIds)), ', ');
        
            $sql = "SELECT c.id
                    FROM agents a
                    JOIN persons p ON a.person_id = p.id
                    JOIN countries c ON p.is_from = c.id 
                    WHERE a.id IN ($placeholders)";
        
            $query = $this->_connexion->prepare($sql);
        
            foreach ($agentsIds as $key => $agentId) {
                $query->bindValue($key + 1, $agentId);
            }
        
            $query->execute();
        
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function getTargetCountry($countryIds) {
            $placeholders = rtrim(str_repeat('?, ', count($countryIds)), ', ');

            $sql = "SELECT p.first_name, p.last_name, c.name, t.id
                    FROM targets t
                    JOIN persons p ON t.person_id = p.id
                    JOIN countries c ON p.is_from = c.id
                    WHERE c.id NOT IN ($placeholders)";

            $query = $this->_connexion->prepare($sql);
        
            foreach ($countryIds as $key => $countryId) {
                $query->bindValue($key + 1, $countryId);
            }
        
            $query->execute();
        
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function insertMission()
        {
            $sql = "INSERT INTO missions (title, description, code_name, start_date, end_date, mission_type, mission_status, required_specialty, mission_stakeout, takes_place_in) VALUES (:title, :description, :codeName, :startDate, :endDate, :type, :status, :specialty, :stakeout, :country)";
            $query = $this->_connexion->prepare($sql);
            
            $query->bindValue(':title', $this->title, PDO::PARAM_STR);
            $query->bindValue(':description', $this->description, PDO::PARAM_STR);
            $query->bindValue(':codeName', $this->codeName, PDO::PARAM_STR);
            $query->bindValue(':startDate', $this->startDate, PDO::PARAM_STR);
            $query->bindValue(':endDate', $this->endDate, PDO::PARAM_STR); 
            $query->bindValue(':type', $this->type, PDO::PARAM_INT);
            $query->bindValue(':status', $this->status, PDO::PARAM_INT);
            $query->bindValue(':specialty', $this->specialty, PDO::PARAM_INT);
            $query->bindValue(':stakeout', $this->stakeout, PDO::PARAM_INT);
            $query->bindValue(':country', $this->country, PDO::PARAM_INT);
            
            $query->execute();
        }

        public function getLastId(){
            return $this->_connexion->lastInsertId();
        }
}


