<?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="text-danger text-center mt-3 bg-pink">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
?>

<h1 class="text-center mt-5">Créer un agent</h1>

<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-6">
            <?php echo $addAgentForm ?>
        </div>
    </div>
</div>
