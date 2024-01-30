<?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="text-danger text-center mt-3 bg-pink">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    if(isset($_SESSION['user'])){
        echo "<h1 class='m-3'> Bonjour Agent " . $_SESSION['user']['last_name'] . "</h1>"; 
    } else {
        echo "<h1 class='m-3'> Bienvenue sur le site du KGB </h1>"; 
    }
?>

<div class="container-fluid">
    <div class="row d-flex justify-content-center">
        <div class="col-5 m-2">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/agent.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Agents</h5>
                    <a href="/agents" class="btn btn-primary">Liste des agents</a>
                </div>
            </div>
        </div>
        <div class="col-5 m-2">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/target.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Cibles</h5>
                    <a href="/targets" class="btn btn-primary">Liste des cibles</a>
                </div>
            </div>
        </div>
        <div class="col-5 m-2">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/contact.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Contacts</h5>
                    <a href="/contacts" class="btn btn-primary">Liste des contacts</a>
                </div>
            </div>
        </div>
        <div class="col-5 m-2">
            <div class="card bg-dark text-white" style="width: 18rem;">
                <img class="card-img-top" src="../../public/images/mission.jpg" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Mssions</h5>
                    <a href="/missions" class="btn btn-primary">Liste des missions</a>
                </div>
            </div>
        </div>
    </div>
</div>


        

