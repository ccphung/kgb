<?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="text-danger text-center mt-3">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
?>

<h1 class="text-center mt-5">Connexion</h1>
<div class="login-container mt-5">
    <form  method ="POST">
        <div class="p-2">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required autofocus/>
        </div>
        <div class="p-2">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <div class="p-2">
            <input type="submit" class="login-btn btn mt-2" value="Se connecter">
        </div>
    </form>
</div>