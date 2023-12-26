<?php
require_once('app/Model.php');

class Mission extends Model {
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
}