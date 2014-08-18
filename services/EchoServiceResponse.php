<?php
    class EchoService extends Service {
        public function validate($errors){
            return true;
        }
       
        public function perform($errors) {
            return new EchoServiceResponse('EchoService', 'Test');
        }
    }
   
    class EchoServiceResponse {
        public $name;
        public $role;
       
        public function __construct($name, $role){
          $this->name = $name;
          $this->role = $role;
        }
         
        function getParsableData(){
            $var = get_object_vars($this);
            foreach($var as &$value){
              if(is_object($value) && method_exists($value,'getParsableData')){
                  $value = $value->getJsonData();
              }
            }
            return $var;
        }
    }
?>
