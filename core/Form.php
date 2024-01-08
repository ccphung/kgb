<?php

class Form
{
    private $formCode ='';
    private $values = [];
    
    public function create()
    {
        return $this->formCode;
    }

    /**
     * Add attributs to tags
     * @param array $attributs
     * @return string 
     */
    public function addAttributs(array $attributs)
    {
        $str ='';

        //Attributs without values
        $shorts = ['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];

        //check if attribut is in shorts array
        foreach($attributs as $attribut => $value){
            if(in_array($attribut, $shorts) && $value == true){
                //add attribut to str
                $str .= " $attribut";
            }
            else {
                //add attribut = value
                $str .= " $attribut='$value'";
            }
        }
        return $str;
    }

    public function debutForm(string $method = '', string $action ='#', array $attributs = []) :self 
    {
        $this->formCode .="<form action='$action' method='$method'";

        if($attributs){
            $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '>';
        }

        $this->formCode .= '>';

        return $this;
    }

    /**
     * Close tag of the form
     * @return Form
     */
    public function endForm():self
    {
        $this->formCode .= '</form>';
        return $this;
    }

    public function debutFieldSet(array $attributs = [], $legend='', $options =''):self
    {
        $this->formCode .= '<fieldset';

        if($attributs){
            $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';
        }

        $this->formCode .= '>';

        $this->formCode .= '<legend>' . $legend . '</legend>';

        $this->formCode .= $options;
        return $this;
    }

    public function endFieldset() :self
    {
        $this->formCode .= '</fieldset>';
    
        return $this;
    }

    public function addLabelFor(string $for, string $text, array $attributs =[], ):self
    {
        $this->formCode .= "<label for='$for'"; 

        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';

        $this->formCode .= ">$text </label>";

        return $this;
    }

    public function addInput(string $type, string $name, array $attributs =[], string $value=''):self
    {
        $this->formCode .= "<input type='$type' name='$name' value='$value'" ; 

        $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '';

        return $this;
    }

    public function addTextArea(string $name, string $value ='', array $attributs =[]):self
    {
        $this->formCode .= "<textarea name='$name'"; 

        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';

        $this->formCode .= ">$value</textarea>";

        return $this;
    }
        
    public function addSelect(string $name, array $options, array $attributs =[], $selectedValue = null) : self
    {   
        $this->formCode .= "<select name='$name'";

        $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '';

        foreach($options as $value => $text) {
            if($value == $selectedValue) {
                $this->formCode .= "<option value='$value' selected>$text</option>";
            } else {
                $this->formCode .= "<option value='$value'>$text</option>";
            }
        }

        $this->formCode .= '</select>';

        return $this;
    }

    public function addButton(string $text, array $attributs = []) :self
    {
        $this->formCode .= '<button ';

        $this->formCode .= $attributs ? $this->addAttributs($attributs) : '';

        $this->formCode .= ">$text</button>";

        return $this;
    }

    public function areFieldsFilled($formData) {
        foreach ($formData as $field) {
            if (empty($field)) {
                return false;
            }
        }
        return true; 
    }
}

 