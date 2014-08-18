<?php
    class Session  {
        public function __construct(){
		  session_start();
        }

        public function get($attribute_name){
			if(isset($_SESSION[$attribute_name])){
				return $_SESSION[$attribute_name];
			}
			return NULL;
		}

        public function put($attribute_name, $attribute_value){
        	$_SESSION[$attribute_name] = $attribute_value;
        }

        public function destroy(){
            session_destroy();
        }
    }
?>