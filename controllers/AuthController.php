<?php

require_once('models/User.php');

class AuthController {
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $authenticated = $this->user->authenticateUser($email, $password);

            if ($authenticated) {
                $this->user->setSession();
                echo "Authentification réussie !";
                header('Location: /home');
                exit();
            } else {
                $_SESSION['error_message'];
                header('Location: /login');
                exit();
            }
        }
    }

    public function logout(){
        unset($_SESSION['user']);
        header('Location: /home');
        exit();
    }
}
