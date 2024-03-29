<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <img src="../../../public/images/kgb.jpg" alt="kgb logo" class="logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
              <li class="nav-item">
                  <a class="nav-link active" href='/'>Accueil</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="/agents">Agents</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="/targets">Cibles</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="/contacts">Contacts</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="/missions">Missions</a>
              </li>
              <?php if(isset($_SESSION['user'])) {
                echo 
                "<li class='nav-item'>
                    <a class='nav-link ff-courrier bg-green' href='/admin'>Espace admin</a>
                </li>";
              }?>
              <li class="nav-item login-nav">
              <a class="nav-link" href="<?php echo isset($_SESSION['user']) ? '/logout' : '/login'; ?>">
                    <span>
                        <i class="fa-solid fa-user"></i>
                        <?php if(isset($_SESSION['user'])) {
                            echo "Se déconnecter";
                        }   else {
                            echo "Se connecter";
                        } ?>
                    </span>
                  </a>
              </li>
          </ul>
      </div>
  </div>
</nav>