<?php
    class UserInfo {
        public $first_name;
		public $last_name;
		public $city;
		public $country;
		public $phone_number;
		public $email;
		public $password;
	
        public function __construct($first_name, $last_name, $city, $country, 
									$phone, $email, $password){
                   $this->first_name = $first_name;
		  $this->last_name = $last_name;
		  $this->city = $city;
		  $this->country = $country;
		  $this->phone_number = $phone;
		  $this->email = $email;
		  $this->password= $password;
        }
		
		public function getFirstName(){
		   return $this->first_name;
		}
		
		public function getLastName(){
		   return $this->last_name;
		}
		
		public function getCity(){
		   return $this->city;
		}	
		
		public function getCountry(){
		   return $this->country;
		}
		
		public function getPhoneNumber(){
		   return $this->phone_number;
		}
		
		public function getEmail(){
		   return $this->email;
		}
		
		public function getPassword(){
		   return $this->password;
		}
    }
?>