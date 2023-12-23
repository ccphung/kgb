<?php
require_once('app/Model.php');

class Target extends Model {
    public function __construct(){
        $this->table = "targets";
        $this->getConnexion();
    }

    public function getAllTargets () {
        $sql = "SELECT p.first_name, p.last_name, p.birth_date, c.name, t.code_name
        FROM targets t
        JOIN persons p ON p.id = t.person_id
        JOIN countries c ON p.is_from = c.id";

        $query = $this->_connexion->prepare($sql);
        $query->execute();
    
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }
}