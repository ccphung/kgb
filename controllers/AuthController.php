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

                if (empty($_POST['email']) || empty($_POST['password'])) {
                    $_SESSION['error_message'] = "Veuillez remplir tous les champs.";
                    header('Location: /login');
                    exit();
                }

                    $email = $_POST['email'];
                    $password = $_POST['password'];
        
                    $cleanedEmail = htmlspecialchars($email);
                    $cleanedPassword = htmlspecialchars($password);

                    $authenticated = $this->user->authenticateUser($cleanedEmail, $cleanedPassword);
        
                    if ($authenticated) {
                        $this->user->setSession();
                        echo "Authentification réussie !";
                        header('Location: /home');
                        exit();
                    } else {
                        $_SESSION['error_message'] = "Adresse email et/ou mot de passe incorrect";
                        header('Location: /login');
                        exit();
                }
            }
        }

    public function logout(){
        unset($_SESSION['user']);
        header('Location: /');
        exit();
    }
}
