<?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="text-danger text-center mt-3 bg-pink">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
?>

<h1 class="text-center mt-5">Créer une mission</h1>

<div class="container">
    <div class="row d-flex justify-content-center">
        <?php echo $addMissionForm ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="../../public/js/filters.js"></script>