<?php

    class CreateUserService extends Service {
        public function validate($errors){
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

           $dbProvider = new DbProvider();
	   $existing_user = $dbProvider->getUser($email);
           
           if( ! Util::isEmpty( $existing_user )  && count($existing_user) > 0 ){
                 $errors->addError("User/Email already exists");
           }

            return true;
        }
       
        public function perform($errors) {
			$first_name = Util::getJsonParams('first_name');
			$last_name = Util::getJsonParams('last_name');
			$city = Util::getJsonParams('city');
			$country = Util::getJsonParams('country');
			$phone_number = Util::getJsonParams('phone_number');
			$email = Util::getJsonParams('email');
			$password = Util::getJsonParams('password');

                        $dbProvider = new DbProvider();
 
			$is_user_created = $dbProvider->createUser($first_name, $last_name, $city, $country, 
									 $phone_number, $email, $password);
                         
                         $user_info = new UserInfo("", "", "", "", "", $email,  $password );

			return new CreateUserServiceResponse( $user_info, $is_user_created);
        }
    }
   
    class CreateUserServiceResponse {
        public $first_name;
        public $last_name;
        public $email;
        public $is_user_created;

        public function __construct($user_info, $is_user_created){
          $this->first_name = $user_info->first_name;
          $this->last_name = $user_info->last_name;
          $this->email = $user_info->email;
          $this->is_user_created = $is_user_created;
        }
    }
?>
