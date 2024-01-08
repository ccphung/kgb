<?php

class Person extends Model {

    public $firstName;
    public $lastName;
    public $birthDate;
    public $nationality;
    public $country;
    public $is_from;
    public $agentId;
    public $personId;
    public $contactId;
    public $targetId;

    public function __construct(){
        $this->table = "persons";
        $this->getConnexion();
    }

    public function insertPerson() {
        $sql = "INSERT INTO persons (first_name, last_name, birth_date, is_from) VALUES (:firstName, :lastName, :birthDate, :nationality)";
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':firstName', $this->firstName, PDO::PARAM_STR);
        $query->bindValue(':lastName', $this->lastName, PDO::PARAM_STR);
        $query->bindValue(':birthDate', $this->birthDate, PDO::PARAM_STR);
        $query->bindValue(':nationality', $this->nationality, PDO::PARAM_INT);
        $query->execute();
    }

    public function getLastId(){
        return $this->_connexion->lastInsertId();
    }

    public function updateAgentPerson()
    {
        $sql = "UPDATE persons
        JOIN agents ON persons.id = agents.person_id
        SET
            persons.first_name = :firstName,
            persons.last_name = :lastName,
            persons.birth_date = :birthDate,
            persons.is_from = :country
        WHERE agents.id = :id";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':firstName', $this->firstName, PDO::PARAM_STR);
        $query->bindValue(':lastName', $this->lastName, PDO::PARAM_STR);
        $query->bindValue(':birthDate', $this->birthDate, PDO::PARAM_STR);
        $query->bindValue(':country', $this->country, PDO::PARAM_INT);

        $query->execute();
    }

    public function updateContactPerson()
    {
        $sql = "UPDATE persons
        JOIN contacts ON persons.id = contacts.person_id
        SET
            persons.first_name = :firstName,
            persons.last_name = :lastName,
            persons.birth_date = :birthDate,
            persons.is_from = :country
        WHERE contacts.id = :contactId";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':contactId', $this->contactId, PDO::PARAM_INT);
        $query->bindValue(':firstName', $this->firstName, PDO::PARAM_STR);
        $query->bindValue(':lastName', $this->lastName, PDO::PARAM_STR);
        $query->bindValue(':birthDate', $this->birthDate, PDO::PARAM_STR);
        $query->bindValue(':country', $this->country, PDO::PARAM_INT);

        $query->execute();
    }

    public function updateTargetPerson()
    {
        $sql = "UPDATE persons
        JOIN targets ON persons.id = targets.person_id
        SET
            persons.first_name = :firstName,
            persons.last_name = :lastName,
            persons.birth_date = :birthDate,
            persons.is_from = :country
        WHERE targets.id = :targetId";

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':targetId', $this->targetId, PDO::PARAM_INT);
        $query->bindValue(':firstName', $this->firstName, PDO::PARAM_STR);
        $query->bindValue(':lastName', $this->lastName, PDO::PARAM_STR);
        $query->bindValue(':birthDate', $this->birthDate, PDO::PARAM_STR);
        $query->bindValue(':country', $this->country, PDO::PARAM_INT);

        $query->execute();
    }
}