<?php
    class Errors  {
        public $errors;
       
        public function __construct(){
          $this->errors = array();
        }
       
        public function addError($errorMsg){
            array_push($this->errors, $errorMsg);
        }
        
        public function getErrors(){
        	return serialize($this->errors);
        }
        
        public function hasError(){
            if (sizeof($this->errors) > 0) {
                return true;
            }
            return false;
        }
    }
?>