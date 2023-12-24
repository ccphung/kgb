<div class="container-fluid">
    <div class="row">
        <h1 class="text-center mt-3">Liste des missions</h1>
        <table class="table table-dark">
            <thead>
                <th>Nom de code</th>
                <th>Titre</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($missions as $mission): ?>
                    <tr>
                        <td><?= ucfirst($mission['code_name']) ?></td>
                        <td><?= ucfirst($mission['title']) ?></td>
                        <td><a href="/missions/<?=$mission['id']?>" class="btn btn-secondary">Accéder au détail</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <nav>
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
</div>