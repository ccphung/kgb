<div>
    <a href="/missions">
        <h4 class="btn btn-secondary mt-2">< Retour</h4>
    </a>
</div>
<div class="container mt-5">
    <h1 class="text-center"><?=$mission['code_name']?></h1>
    <h3 class="text-center color-red"><?=$mission['title']?></h3>
    <h5 class="text-center 
        <?php 
            switch ($datas[0]['status']) {
                case "En préparation":
                    echo "text-warning";
                    break;
                case "En cours":
                    echo "text-primary";
                    break;    
                case "Terminé":
                    echo "text-success";
                    break;
                case "Echec":
                    echo "text-danger";
                    break;
            }
        ?>"> 
        Statut : <?=$datas[0]['status']?>
    </h5>
    
    <div class="d-flex justify-content-center text-center">
        <div class="col-8 m-2 p-2 border border-white">
                <h5 class="card-title text-center">Objectif</h5>
                <p class="card-text"><?=$mission['description']?></p>
        </div>
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-8 col-md-4 d-flex flex-column">
            <div class="col card m-2 bg-dark">
                <div class="card-body">
                    <h5 class="card-title text-white"><i class="fa-solid fa-user-tie"></i></i> Agents</h5>
                    <ul>
                        <?php
                            foreach ($agents as $agent) {
                            echo 
                            '<li class="card-text text-white">'. ucfirst($agent['first_name']) . ' ' . ucfirst($agent['last_name']). ' ( Agent ' . $agent['agent_code'] . ' ) </li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="col card m-2 bg-dark">
                <div class="card-body">
                    <h5 class="card-title text-white"><i class="fa-solid fa-bullseye"></i> Cibles</h5>
                    <ul>
                        <?php
                            foreach ($targets as $target) {
                            echo 
                            '<li class="card-text text-white">' . ucfirst($target['first_name']) . ' ' . ucfirst($target['last_name']). ' (' . $target['code_name'] . ') </li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col card m-2 bg-dark">
                <div class="card-body">
                    <h5 class="card-title text-white"><i class="fa-solid fa-address-book"></i> Contacts</h5>
                    <ul>
                        <?php
                            foreach ($contacts as $contact) {
                            echo 
                            '<li class="card-text text-white">' . ucfirst($contact['first_name']) . ' ' . ucfirst($contact['last_name']). ' (' . $contact['code_name'] . ') </li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-8">
            <div class="card m-2 bg-dark">
                    <div class="card-body text-white">
                        <h5 class="card-title">
                            <i class="fa-solid fa-list"></i> 
                            Détails
                        </h5>
                        <ul>
                            <li>
                                Type : <?= $datas[0]['mission_type']?>
                            </li>
                            <li>
                                Localisation : <?= $datas[0]['mission_country']?>
                            </li>
                            <li>
                                Spécialité requise : <?= $datas[0]['required_specialty']?>
                            </li>
                            <li class="card-text"> Dates : 
                                <ul>
                                    <li class="card-text">
                                        Début : <?= $mission['start_date']?>
                                    </li>
                                    <?php if($datas[0]['status'] !== "En préparation" &&  $datas[0]['status'] !== "En cours") {
                                        echo "<li class='card-text'>Fin :" .  $mission['end_date'] ."</li>";
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card m-2 bg-dark">
                    <div class="card-body ">
                        <h5 class="card-title text-white" ><i class="fa-solid fa-house"></i></i> Planque</h5>
                        <ul>
                            <li class="card-text text-white">Code : <?=$datas[0]['code'];?></li>
                            <li class="card-text text-white">Adresse : <?=$datas[0]['address'];?></li>
                            <li class="card-text text-white">Pays : <?=$datas[0]['stakeout_country'];?></li>
                            <li class="card-text text-white">Type : <?=$datas[0]['type'];?></li>   
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

