<?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="text-success text-center mt-3 bg-green">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
?>

<div class="container-fluid">
    <?php     
    if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
        echo  '<a class="btn btn-primary mt-3" href="/agents/add">Ajouter un agent</a>';
    }
    ?>

    <h1 class="text-center mt-3">Liste des agents</h1>
    <table class="table table-dark" id="myTable">
        <thead>
            <th>Code Agent</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Date de naissance</th>
            <th>Spécialtés</th>
            <th>Pays</th>
            <th>Action</th>
        </thead>
        <tbody>
            <?php foreach ($agents as $agent): ?>
                <tr>
                    <td><?= $agent['agent_code'] ?></td>
                    <td><?= ucfirst($agent['first_name']) ?></td>
                    <td><?= ucfirst($agent['last_name']) ?></td>
                    <td><?= date('d-m-Y', strtotime($agent['birth_date']))?></td>
                    <td>                    
                        <ul>
                            <?php foreach ($specialties as $key => $specialty) : ?>
                                <?php if($specialty['agent_id'] == $agent['id']){
                                    echo '<li>'. $specialty['name'].'</li>' ;
                                } else {
                                    echo '';
                                } 
                                ?>
                            <?php endforeach; ?></td>
                        </ul>
                    </td>
                    <td><?= $agent['name'] ?></td>
                    <td>
                        <a href="/admin/agent/modify/<?=$agent['id']?>" class="btn btn-warning mx-2">Modifier</a>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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


