<?php

class Awesome_Validator {
    /* array of validation messages */
    protected $messages;
    protected $model;

    public function __construct($model = ''){
        $model = 'A_Team_App_Model_'.ucfirst($model);
        $this->model = $model;
        $this->fill_messages();
    }

    /**
     * Fill validation messages for all rules
     */
    protected function fill_messages(){
        $this->messages = array(
            'required' => __('%1$s %2$s is required.')
        );
    }

    /**
     * Validation method for all routes
     *
     * @param array $request
     * @internal param $action
     * @return array
     */
    public function validate($request){
        $model = $this->model;
        $rules = $model::$validation_rules;
        $errors = new WP_Error();

        foreach($request as $key => $value){
            /* Remove model name from request fields
             * For example, by default model name field for Employer is employer-name
             */
            //$fixed_key = str_replace($model::$model_name . '_', '', $key);
            if(array_key_exists($key, $rules)){
                $rule = $rules[$key]['rule'];
                $field = trim($value);

                /* Check if filed required (must not be empty) */
                if($rule == 'required' && empty($field)){
                    $message = sprintf($this->messages[$rule], $model::getLabel('singular_name'), ucfirst($key));
                    $errors->add($key, $message);
                }
            }
        }
        return $errors;
    }
} 