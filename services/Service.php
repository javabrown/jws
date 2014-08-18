<?php
    abstract class Service {
        abstract public function validate($errors);
        abstract public function perform($errors);	
		
		public function is_authenticated(){
			$email = $session->get('email'); 
            $internal_key = Util::encrypt($email); 
            $is_logged_in = $session->get('active');
			
			$client_key = Util::getJsonParams('client_key');
			
			if(!Util::isEmpty($is_logged_in) && $is_logged_in == true && !Util::isEmpty($internal_key) && $internal_key === $client_key){
				return true;
			}
			
			return false;
		}
		
        public function getSession(){
                return new Session();
        }
    }
?>