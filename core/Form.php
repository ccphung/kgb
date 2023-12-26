<?php

class Form 
{
    private $formCode = '';

    public function create(){

        return $this->formCode; 
    }

    /**
     * Valide if all fields are filled
     * @param array $form
     * @param array $fields
     * @return bool
     */

    public static function validate(array $form, array $fields) : bool
    {
        foreach($fields as $field) {
            if (!isset($form[$field]) || empty($form[$field])){
                return false;
            }
            return true;
        }
    }

}