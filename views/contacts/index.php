<div class="container-fluid">
    <div class="row">
        <h1 class="text-center mt-3">Liste des contacts</h1>
        <?php foreach ($contacts as $contact): ?>
            <div class="col m-3 d-flex justify-content-center">
                <div class="card" style="width: 18rem;">
                    <img src="../../public/images/contact.jpg" class="card-img-top" alt="top secret">
                    <div class="card-body">
                        <h5 class="card-title"><?= $contact['first_name'] ?> <?= $contact['last_name'] ?></h5>
                        <p class="card-text">
                            <i class="fa-solid fa-cake-candles"></i>
                            <?= $contact['birth_date'] ?>
                        </p>
                        <p class="card-text">
                            <i class="fa-solid fa-globe"></i>
                            <?= $contact['name'] ?>
                        </p>
                        <p class="card-text">
                            <i class="fa-solid fa-barcode"></i>
                            <?= $contact['code_name'] ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>




