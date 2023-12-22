<?php 
foreach ($agents as $agent): ?>
    <?= $agent['first_name'] ?> <?= $agent['last_name'] ?> - <?= $agent['birth_date'] ?> - <?= $agent['name'] ?> - <?= $agent['agent_code'] ?>
<?php endforeach; ?>