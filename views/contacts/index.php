<?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="text-success text-center mt-3 bg-green">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
?>

<div class="container-fluid">
<?php     
    if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
        echo  '<a class="btn btn-primary mt-3" href="/contacts/add">Ajouter un contact</a>';
    }
    ?>
    <div class="row">
        <h1 class="text-center mt-3">Liste des contacts</h1>
        <table class="table table-dark" id="myTable">
            <thead>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Nom de code</th>
                <th>Date de naissance</th>
                <th>Pays</th>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= ucfirst($contact['first_name']) ?></td>
                        <td><?= ucfirst($contact['last_name']) ?></td>
                        <td><?= ucfirst($contact['code_name']) ?></td>
                        <td><?= date('d-m-Y', strtotime($contact['birth_date']))?></td>
                        <td><?= $contact['name'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <nav class="mt-3">
        <ul class="pagination">
            <li class="page-item <?= $currentPage == 1 ? "disabled" : ""?>">
                <a href="?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
            </li>
            <?php for ($i = 1; $i <= $pagination['pages']; $i++): ?>
                <li class="page-item <?= $currentPage == $i ? "active" : "" ?>">
                    <a href="?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage == $pagination['pages'] ? "disabled" : ""?>">
                <a href="?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
            </li>
        </ul>
    </nav>
</div>




