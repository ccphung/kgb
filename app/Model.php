<?php

class Model {
    private $host = "localhost";
    private $db_name = "kgb";
    private $username = 'root';
    private $password = '';

    //Protected : les enfants doivent pouvoir la modifier
    protected $_connexion;

    //public : doit être modifiable
    public $table;
    public $id;

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

    public function pagination() {
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1 ;

        // Récupérer le nombre total d'éléments
        $sql = "SELECT COUNT(*) AS total FROM " . $this ->table;
        $query= $this->_connexion->prepare($sql);
        $query->execute();
        $result = $query->fetch();
        $total =(int) $result['total'];
        
        //Nombre d'éléments par page
        $perPage = 4;

        //Nombre de pages nécessaires (arrondi au supérieur)
        $pages = ceil($total / $perPage);

        $pages = intval($pages);
        
        $first = ($currentPage * $perPage) - $perPage;

        return ['first' => $first, 'perPage' => $perPage, 'pages' => $pages];
    }
    }