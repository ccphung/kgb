<?php
foreach ($missions as $mission): ?>
<p><?=$mission['title']?></p>
<a href="/missions/<?=$mission['id']?>">Accéder au détail de la mission</a>
<?php endforeach; ?>