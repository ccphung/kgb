<div class="container-fluid">
    <div class="row">
        <?php foreach ($missions as $mission): ?>
            <div class="col m-3 d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <img src="../../public/images/top-secret.jpg" class="card-img-top" alt="top secret">
                    <div class="card-body">
                        <h5 class="card-title"><?=$mission['code_name']?></h5>
                        <p class="card-text"><?=$mission['title']?></p>
                        <a href="/missions/<?=$mission['id']?>" class="btn btn-secondary">Accéder au détail</a>
                    </div>
                    </div>
                </div>
        <?php endforeach; ?>        
    </div>
</div>