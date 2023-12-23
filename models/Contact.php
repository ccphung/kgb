<?php
require_once('app/Model.php');

class Contact extends Model {
    public function __construct(){
        $this->table = "contacts";
        $this->getConnexion();
    }

    public function getAllContacts () {
        $sql = "SELECT p.first_name, p.last_name, p.birth_date, cn.name, c.code_name
        FROM contacts c
        JOIN persons p ON p.id = c.person_id
        JOIN countries cn ON p.is_from = cn.id";

        $query = $this->_connexion->prepare($sql);
        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }
}