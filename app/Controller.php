<?php
abstract class Controller {
    public function loadModel(string $model) {
        require_once(__DIR__.'../../models/'.$model.'.php');
        $this->$model = new $model();
    }
}