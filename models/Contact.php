<?php
require_once('app/Model.php');

class Contact extends Model {
    public $codeName;
    public $personId;
    public $contactId;

    public function __construct(){
        $this->table = "contacts";
        $this->getConnexion();
    }

    public function getAllContacts () {
        $paginationInfo = $this->pagination();

        $sql = "SELECT p.first_name, p.last_name, p.birth_date, cn.name, c.code_name, c.id, cn.id AS country_id
        FROM contacts c
        JOIN persons p ON p.id = c.person_id
        JOIN countries cn ON p.is_from = cn.id
        ORDER BY p.last_name ASC LIMIT :first, :perPage";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':first', $paginationInfo['first'], PDO::PARAM_INT);
        $query->bindValue(':perPage', $paginationInfo['perPage'], PDO::PARAM_INT);

        $query->execute();
        
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function insertContact()
    {
        $this->getConnexion();

        $sql = "INSERT INTO contacts (person_id, code_name) VALUES (:personId, :codeName)";
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':personId', $this->personId, PDO::PARAM_INT);
        $query->bindValue(':codeName', $this->codeName, PDO::PARAM_STR);
        
        $query->execute();

    }

    public function getContactInfo($contactId) 
    {
        $sql = "SELECT c.id, c.code_name, p.first_name, p.last_name, cn.id AS country_id, p.birth_date
        FROM contacts c
        JOIN persons p ON p.id = c.person_id
        JOIN countries cn ON p.is_from = cn.id
        WHERE c.id = :contactId";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':contactId', $contactId, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function updateContact()
    {
        $sql = "UPDATE contacts 
        SET code_name = :codeName
        WHERE id = :contactId";
        
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':contactId', $this->contactId, PDO::PARAM_INT);
        $query->bindValue(':codeName', $this->codeName, PDO::PARAM_STR);
        
        $query->execute();
    }

    public function deleteContact()
    {
        $queryDeleteTargetMission = "DELETE FROM contact_mission WHERE contact_id = :contactId";
        $queryDeleteTargetMission = $this->_connexion->prepare($queryDeleteTargetMission);
        $queryDeleteTargetMission->bindParam(':contactId', $this->contactId, PDO::PARAM_INT);
        $queryDeleteTargetMission->execute();
    
        $sqlDeleteTarget = "DELETE FROM contacts WHERE id = :contactId";
        $sqlDeleteTarget = $this->_connexion->prepare($sqlDeleteTarget);
        $sqlDeleteTarget->bindParam(':contactId', $this->contactId, PDO::PARAM_INT);
        $sqlDeleteTarget->execute();
    
        $sqlDeletePerson = "DELETE FROM persons WHERE id = :personId";
        $queryDeletePerson = $this->_connexion->prepare($sqlDeletePerson);
        $queryDeletePerson->bindParam(':personId', $this->personId, PDO::PARAM_INT);
        $queryDeletePerson->execute();
    }
}