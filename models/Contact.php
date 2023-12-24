<?php
require_once('app/Model.php');

class Contact extends Model {
    public function __construct(){
        $this->table = "contacts";
        $this->getConnexion();
    }

    public function getAllContacts () {
        $paginationInfo = $this->pagination();

        $sql = "SELECT p.first_name, p.last_name, p.birth_date, cn.name, c.code_name
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
}