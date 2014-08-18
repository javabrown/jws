<?php
    class SignoutService extends Service {
        public function validate($errors){
            return true;
        }
       
        public function perform($errors) {
			if( !Util::isEmpty( parent::getSession()->get('email') ) &&  !Util::isEmpty( parent::getSession()->get('active') ) ){
				parent::getSession()->destroy();
                return new SignoutServiceResponse(true, parent::getSession());
            }
			
            return new SignoutServiceResponse(false, parent::getSession());
        }
    }
   
    class SignoutServiceResponse {
        public $is_logged_out;
		public $is_active;
		
        public function __construct($logged_out, $session){
          $this->is_logged_out = $logged_out;		
          $this->is_active = !Util::isEmpty( $session->get('active') );
        }
    }
?>