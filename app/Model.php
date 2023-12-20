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

    public function getConnection(){
        //on efface la connexion précédente
        $this->_connexion = null;

        try {
            $this->_connexion = new PDO('mysql:host='.$this->host.';dbame='.$this->db_name, $this->username, $this->password);

        } catch(PDOException $e){
                echo "Erreur :" . $e->getMessage();
            }

        //     $_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //     $emailForm = $_POST['email'];
        //     $passwordForm = $_POST['password'];

        //     $query = "SELECT * FROM admins WHERE email= :email ";
        //     $stmt = $pdo->prepare($query);
        //     $stmt->bindParam(':email', $emailForm);
        //     $stmt->execute();

        //     if($stmt->rowCount() == 1){
        //         $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        //         if(password_verify($passwordForm, $admin['password'])){
        //             header("Location: /home");
        //             exit();
        //         } else {
        //             $_SESSION['error_message'] = "Mot de passe incorrect";
        //         }

        //     } else {
        //         $_SESSION['error_message'] = "Utilisateur non trouvé";
        //     }
        // }
        // catch(PDOException $e){
        //     echo "Erreur de connexion à la base de données :" . $e->getMessage();
        // }

        // if (isset($_SESSION['error_message'])) {
        //     echo $_SESSION['error_message'];
        //     unset($_SESSION['error_message']);
        // } else {
        //     echo "Connecté avec succès";
        // }

        //     require_once 'views/login.php';
        // }
    }
}