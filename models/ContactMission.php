<?php
require_once('app/Model.php');

class ContactMission extends Model {
    public $contactId;
    public $missionId;

    public function __construct(){
        $this->table = "contact_mission";
        $this->getConnexion();
    }

    public function insertContactMission() {
        $sql = "INSERT INTO contact_mission (contact_id, mission_id) VALUES (:contactId, :missionId)";
        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':contactId', $this->contactId, PDO::PARAM_INT);
        $query->bindValue(':missionId', $this->missionId, PDO::PARAM_INT);
        $query->execute();
    }
}