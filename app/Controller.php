<?php
abstract class Controller {
    public function loadModel(string $model) {
        require_once(BASE_URL.'\\models\\'.$model.'.php');
        $this->$model = new $model();
    }

    public function render(string $file){
        require_once(BASE_URL.'\\views\\'.strtolower(get_class($this)).'\\'.$file.'.php');
    }

}