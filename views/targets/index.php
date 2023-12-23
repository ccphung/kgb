<div class="container-fluid">
    <div class="row">
        <h1 class="text-center mt-3">Liste des cibles</h1>
        <?php foreach ($targets as $target): ?>
            <div class="col m-3 d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <img src="../../public/images/target.png" class="card-img-top" alt="target">
                    <div class="card-body">
                        <h5 class="card-title"><?= $target['first_name'] ?> <?= $target['last_name'] ?></h5>
                        <p class="card-text">
                            <i class="fa-solid fa-cake-candles"></i>
                            <?= $target['birth_date'] ?>
                        </p>
                        <p class="card-text">
                            <i class="fa-solid fa-globe"></i>
                            <?= $target['name'] ?>
                        </p>
                        <p class="card-text">
                            <i class="fa-solid fa-barcode"></i>
                            <?= $target['code_name'] ?>
                        </p>
                    </div>
                </div>
        </div>
            <?php endforeach; ?>
            </div>
    </div>
</div>




