<?php

class Person extends Model {

    public $firstName;
    public $lastName;
    public $birthDate;
    public $nationality;

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

}