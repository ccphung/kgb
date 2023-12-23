<div class="container-fluid">
    <div class="row">
        <h1 class="text-center mt-3">Liste des agents</h1>
        <?php foreach ($agents as $agent): ?>
            <div class="col m-3 d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <img src="../../public/images/agent.jpg" class="card-img-top" alt="top secret">
                    <div class="card-body">
                        <h5 class="card-title"><?= $agent['first_name'] ?> <?= $agent['last_name'] ?></h5>
                        <p class="card-text">
                            <i class="fa-solid fa-cake-candles"></i>
                            <?= $agent['birth_date'] ?>
                        </p>
                        <p class="card-text">
                            <i class="fa-solid fa-globe"></i>
                            <?= $agent['name'] ?>
                        </p>
                        <p class="card-text">
                            <i class="fa-solid fa-barcode"></i>
                            <?= $agent['agent_code'] ?>
                        </p>
                    </div>
                </div>
        </div>
            <?php endforeach; ?>
            </div>
    </div>
</div>




