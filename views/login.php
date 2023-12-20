<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap"    rel="stylesheet">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="icon" type="image/png" href="../public/images/kgb.jpg" />
    <title>Connexion</title>
</head>
<body>
    <h1 class="login-title text-center mt-5">Merci de vous authentifier</h1>
    <div class="login-container mt-5">
        <form method ="POST">
            <div class="p-2">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" required />
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>