<div class="container mt-5">
    <h1 class="text-center text-danger"><?=$mission['code_name']?></h1>
    <h3 class="text-center"><?=$mission['title']?></h3>
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
    
    <div class="d-flex justify-content-center">
        <div class="col-8 card m-2">
            <div class="card-body">
                <h5 class="card-title text-center">Objectif</h5>
                <p class="card-text"><?=$mission['description']?></p>
            </div>
        </div>
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-8 col-md-3 card m-2 bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-user-tie"></i></i> Agents</h5>
                <ul>
                    <?php
                        foreach ($agents as $agent) {
                        echo 
                        '<li class="card-text">'. ucfirst($agent['first_name']) . ' ' . ucfirst($agent['last_name']). ' ( Agent ' . $agent['agent_code'] . ' ) </li>';
                        }
                    ?>
                </ul>
            </div>
        </div>

        <div class="col-8 col-md-3 card m-2 bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-bullseye"></i> Cibles</h5>
                <ul>
                    <?php
                        foreach ($targets as $target) {
                        echo 
                        '<li class="card-text">' . ucfirst($target['first_name']) . ' ' . ucfirst($agent['last_name']). ' (' . $target['code_name'] . ') </li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
        <div class="col-8 col-md-3 card m-2 bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-address-book"></i> Contacts</h5>
                <ul>
                    <?php
                        foreach ($contacts as $contact) {
                        echo 
                        '<li class="card-text">' . ucfirst($contact['first_name']) . ' ' . ucfirst($agent['last_name']). ' (' . $contact['code_name'] . ') </li>';
                        }
                    ?>
                </ul>
            </div>
        </div>

            <div class="row d-flex justify-content-center">
                <div class="col-8 col-md-5 card m-2 bg-light">
                    <div class="card-body">
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
                <div class="col-8 col-md-5 card m-2">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-house"></i></i> Planque</h5>
                        <ul>
                            <li>Code : <?=$datas[0]['code'];?></li>
                            <li>Adresse : <?=$datas[0]['address'];?></li>
                            <li>Pays : <?=$datas[0]['stakeout_country'];?></li>
                            <li>Type : <?=$datas[0]['type'];?></li>   
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
