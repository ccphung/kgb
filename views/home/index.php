<?php
    if(isset($_SESSION['user'])){
        echo "<h1 class='m-3'> Bonjour Agent " . $_SESSION['user']['last_name'] . "</h1>"; 
    } else {
        echo "<h1 class='m-3'> Bienvenue sur le site du KGB </h1>"; 
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-6 m-4">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/agent.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Agents</h5>
                    <ul>
                        <li class="card-text">Nom</li>
                        <li class="card-text">Spécialités</li>
                        <li class="card-text">Date de naissance</li>
                        <li class="card-text">Pays</li>
                    </ul>

                    <a href="/agents" class="btn btn-primary">Liste de agents</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 m-4">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/target.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Cibles</h5>
                    <ul>    
                        <li class="card-text">Nom</li>
                        <li class="card-text">Date de naissance</li>
                        <li class="card-text">Pays</li>
                    </ul>
                    <a href="/targets" class="btn btn-primary">Liste de cibles</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 m-4">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/contact.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Contacts</h5>
                    <ul>    
                        <li class="card-text">Nom</li>
                        <li class="card-text">Date de naissance</li>
                        <li class="card-text">Pays</li>
                    </ul>
                    <a href="/contacts" class="btn btn-primary">Liste de contact</a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 m-4">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/mission.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Mssions</h5>
                    <ul>    
                        <li class="card-text">Détails</li>
                        <li class="card-text">Status</li>
                        <li class="card-text">Planque</li>
                        <li class="card-text">Agents/Cibles/Contacts</li>
                    </ul>
                    <a href="/missions" class="btn btn-primary">Liste de missions</a>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>


        

