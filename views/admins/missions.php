<div class="row">
        <h1 class="text-center mt-3">Liste des missions</h1>
        <table class="table table-dark" id="myTable">
            <thead>
                <th>Nom de code</th>
                <th>Titre</th>
                <th>Status</th>
                <th>Action</th>
            </thead>
            <tbody>
                <?php foreach ($missions as $mission): ?>
                    <tr>
                        <td><?= ucfirst($mission['code_name'])?></td>
                        <td><?= ucfirst($mission['title']) ?></td>
                        <td class="
                            <?php 
                            switch ($mission['status']) {
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
                            }?>">
                            <?= ucfirst($mission['status']) ?>
                        </td>
                        <td>
                            <a href="/admin/mission/modify/<?=$mission['id']?>" class="btn btn-warning">Modifier</a>
                            <a href="" class="btn btn-danger">Supprimer</a>
                        </td>
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
</div>