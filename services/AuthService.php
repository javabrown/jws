<?php
    class AuthService extends Service {
        public function validate($errors){
	    /*Validate session if user already logged-in.*/
 
            $email = parent::getSession()->get('email');
            $is_active = parent::getSession()->get('active');
            $first_name = parent::getSession()->get('first_name');
            $last_name = parent::getSession()->get('last_name');
            
            if( !Util::isEmpty($email)  && !Util::isEmpty($is_active)  ){
                    return true;
            }

			
			/*Validate User Input for Login Field*/
			$email = Util::getJsonParams('email');
			$password = Util::getJsonParams('password');

			if( Util::isEmpty( $email ) || Util::isEmpty( $password )){
				if( Util::isEmpty( $email )  ) {
					 $errors->addError("email is missing");
				}

				if( Util::isEmpty( $password )  ) {
					 $errors->addError("password is missing");
				}

				return false;
			}

			/*Check for User Info into Database.*/
            $dbProvider = new DbProvider();
			$user_info = $dbProvider->getUser($email); 
			 
			if(!isset($user_info) || strcasecmp( $user_info->getPassword(), $password ) != 0 ){
			   $errors->addError("Invalid Login or Password");
               return false;
			}

            return true;
        }
       
        public function perform($errors) {
            if( !Util::isEmpty( parent::getSession()->get('email') ) &&  !Util::isEmpty( parent::getSession()->get('active') ) ){
                return new AuthServiceResponse( parent::getSession() );
            }

            $dbProvider = new DbProvider();
			$email = Util::getJsonParams('email');
	        $user_info = $dbProvider->getUser($email); 	
                
            if(isset( $user_info )){ 
               parent::getSession()->put('email' , $user_info->email);
               parent::getSession()->put('active', 'true');
               parent::getSession()->put('first_name', $user_info->first_name);
               parent::getSession()->put('last_name', $user_info->last_name);
               //return new AuthServiceResponse( true );
            }

            return new AuthServiceResponse( parent::getSession() );
        }
    }
   
    class AuthServiceResponse {
		public $first_name;
		public $last_name;		
		public $email;
                public $client_key;
                public $is_logged_in;
		
        public function __construct($session){
          $this->first_name = $session->get('first_name');		
          $this->last_name = $session->get('last_name');
          $this->email = $session->get('email'); 
          $this->client_key= Util::encrypt($this->email); 
          $this->is_logged_in = $session->get('active');
        }
    }
?>