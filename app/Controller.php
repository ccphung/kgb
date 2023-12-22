<?php

abstract class Controller {
    public function loadModel(string $model) {
        require_once(BASE_URL.'\\models\\'.$model.'.php');
        $this->$model = new $model();
    }

    public function render(string $file, array $data = []){
        extract($data);

        //Démarrage du buffer
        ob_start();

        require_once(BASE_URL.'\\views\\'.strtolower(get_class($this)).'\\'.$file.'.php');

        $content = ob_get_clean();

        require_once(BASE_URL.'/views/layouts/default.php');
    }

}