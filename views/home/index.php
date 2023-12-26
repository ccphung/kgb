<?php
    if(isset($_SESSION['user'])){
        echo "<h1 class='m-3'> Bonjour agent " . $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'] . "</h1>"; 
    }
?>

        

