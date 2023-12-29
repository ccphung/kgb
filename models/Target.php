<?php
require_once('app/Model.php');

class Target extends Model {
    public $codeName;
    public $personId;

    public function __construct(){
        $this->table = "targets";
        $this->getConnexion();
    }

    public function getAllTargets () {
        $paginationInfo = $this->pagination();
        
        $sql = "SELECT p.first_name, p.last_name, p.birth_date, c.name, t.code_name
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
}