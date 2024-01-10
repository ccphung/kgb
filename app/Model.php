<?php

class Model {
    private $host = "eu-cluster-west-01.k8s.cleardb.net";
    private $db_name = "heroku_9b2c035b4dce5e1";
    private $username = 'bd67e1f07f9128';
    private $password = '81ef2b2f';

    //Protected : les enfants doivent pouvoir la modifier
    protected $_connexion;

    //public : doit être modifiable
    public $table;
    public $id;

    public function __construct() {
        $this->_connexion = $this->getConnexion();
    }

    public function getConnexion(){
        //Efface la connexion précédente
        $this->_connexion = null;

        try {
            $this->_connexion = new PDO('mysql:host='.$this->host.'; dbname='.$this->db_name,
            $this->username, $this->password);

             $this->_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             return $this->_connexion;

        } catch(PDOException $e){
                echo "Erreur :" . $e->getMessage();
                return null;
            }
    }

    public function getAll() {
        $paginationInfo = $this->pagination();

        $sql = "SELECT * FROM " . $this->table;

        $query = $this->_connexion->prepare($sql);

        $query = $this->_connexion->prepare($sql);
        $query->bindValue(':first', $paginationInfo['first'], PDO::PARAM_INT);
        $query->bindValue(':perPage', $paginationInfo['perPage'], PDO::PARAM_INT);

        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }

    public function getOne() {
        $sql = "SELECT * FROM " . $this->table . " WHERE id=" . $this->id;
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        return $query->fetch();
    }

    public function getCountries() {
        $sql = "SELECT * FROM countries";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getSpecialties() {
        $sql = "SELECT * FROM specialties";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getTypes() {
        $sql = "SELECT * FROM types";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getStatus() {
        $sql = "SELECT * FROM status";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getStakeouts() {
        $sql = "SELECT * FROM stakeouts";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAgents() {
        $sql = "SELECT p.first_name, p.last_name
                FROM persons p
                JOIN agents a ON a.person_id = p.id";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getContacts() {
        $sql = "SELECT p.first_name, p.last_name
                FROM persons p
                JOIN contacts c ON c.person_id = p.id";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public function getTargets() {
        $sql = "SELECT p.first_name, p.last_name
                FROM persons p
                JOIN targets t ON t.person_id = p.id";
        $query = $this ->_connexion->prepare($sql);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function pagination() {
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1 ;

        // Récupérer le nombre total d'éléments
        $sql = "SELECT COUNT(*) AS total FROM " . $this ->table;
        $query= $this->_connexion->prepare($sql);
        $query->execute();
        $result = $query->fetch();
        $total =(int) $result['total'];
        
        //Nombre d'éléments par page
        $perPage = 5;

        //Nombre de pages nécessaires (arrondi au supérieur)
        $pages = ceil($total / $perPage);

        $pages = intval($pages);
        
        $first = ($currentPage * $perPage) - $perPage;

        return ['first' => $first, 'perPage' => $perPage, 'pages' => $pages];
    }
    }