<?php

require_once('app/Model.php');

class User extends Model {
    public $id;
    private $email;
    private $first_name;
    private $last_name;

    public function __construct(){
        $this->table = "admins";
        $this->getConnexion();
    }

    public function authenticateUser($email, $password) {

        $sql = "SELECT * FROM admins WHERE email = :email";
        $query = $this->_connexion->prepare($sql);

        $query->bindParam(':email', $email);
        $query->execute();

        if($query->rowCount() == 1){
            $user = $query->fetch(PDO::FETCH_ASSOC);
    
            if(password_verify($password, $user['password'])){
                $this->id = $user['id'];
                $this->email = $user['email'];
                $this->first_name = $user['first_name'];
                $this->last_name = $user['last_name'];
            } else {
                return false;
            }
        } else {
            return false;
        } return true;
    }

    /**
     * Create new session
     * @return void
     */
    public function setSession() {
        $_SESSION['user'] = [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name
        ];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    }