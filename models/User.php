<?php

class User {
    private $email;
    
    public function __construct(string $email) {
        $this->email = $email;
    }
    
    public function getEmail() : string {
        return $this->email;
    }
    public function setEmail(string $email) {
        $this->email = $email;
    }
       
    public function getMessage()  : string {
            return "Bonjour " . $this->email . ", bienvenue sur notre site !";
        }
    }