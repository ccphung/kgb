<?php
require_once('app/Model.php');

class Target extends Model {
    public $codeName;
    public $personId;
    public $targetId;

    public function __construct(){
        $this->table = "targets";
        $this->getConnexion();
    }

    public function getAllTargets () {
        $paginationInfo = $this->pagination();
        
        $sql = "SELECT p.first_name, p.last_name, p.birth_date, c.name, t.code_name, t.id, c.id AS country_id
        FROM targets t
        JOIN persons p ON p.id = t.person_id
        JOIN countries c ON p.is_from = c.id
        ORDER BY p.last_name ASC LIMIT :first, :perPage";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':first', $paginationInfo['first'], PDO::PARAM_INT);
        $query->bindValue(':perPage', $paginationInfo['perPage'], PDO::PARAM_INT);

        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function insertTarget()
    {
        $this->getConnexion();

        $sql = "INSERT INTO targets (person_id, code_name) VALUES (:personId, :codeName)";
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':personId', $this->personId, PDO::PARAM_INT);
        $query->bindValue(':codeName', $this->codeName, PDO::PARAM_INT);
        
        $query->execute();

    }

    public function getTargetInfo($targetId) 
    {
        $sql = "SELECT t.id, t.code_name, p.first_name, p.last_name, cn.id AS country_id, p.birth_date, p.id AS person_id
        FROM targets t
        JOIN persons p ON p.id = t.person_id
        JOIN countries cn ON p.is_from = cn.id
        WHERE t.id = :targetId";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':targetId', $targetId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public function updateTarget()
    {
        $sql = "UPDATE targets 
        SET code_name = :codeName
        WHERE id = :targetId";
        
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':targetId', $this->targetId, PDO::PARAM_INT);
        $query->bindValue(':codeName', $this->codeName, PDO::PARAM_STR);
        
        $query->execute();
    }

    public function deleteTarget()
    {
        $queryDeleteTargetMission = "DELETE FROM target_mission WHERE target_id = :targetId";
        $queryDeleteTargetMission = $this->_connexion->prepare($queryDeleteTargetMission);
        $queryDeleteTargetMission->bindParam(':targetId', $this->targetId, PDO::PARAM_INT);
        $queryDeleteTargetMission->execute();
    
        $sqlDeleteTarget = "DELETE FROM targets WHERE id = :targetId";
        $sqlDeleteTarget = $this->_connexion->prepare($sqlDeleteTarget);
        $sqlDeleteTarget->bindParam(':targetId', $this->targetId, PDO::PARAM_INT);
        $sqlDeleteTarget->execute();
    
        $sqlDeletePerson = "DELETE FROM persons WHERE id = :personId";
        $queryDeletePerson = $this->_connexion->prepare($sqlDeletePerson);
        $queryDeletePerson->bindParam(':personId', $this->personId, PDO::PARAM_INT);
        $queryDeletePerson->execute();
    }
}