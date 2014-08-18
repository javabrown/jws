<?php
    class StorageService extends Service {

        public function validate($errors){
	        $client_key = Util::getJsonParams('client_key');
	        $store_name = Util::getJsonParams('store_name');
            $store_data = Util::getJsonParams('store_data');
            $store_action = Util::getJsonParams('store_action');
            $message_type = Util::getJsonParams('message_type');

			if(Util::isEmpty( $client_key ) || !Util::is_authenticated( parent::getSession() )){
                           error_log("is_auth=". Util::is_authenticated( parent::getSession() ) );
			  // $errors->addError("missing client_key or invalid authentication");
			}
			
			if( Util::isEmpty( $store_name ) || Util::isEmpty( $store_data ) || Util::isEmpty( $store_action ) || Util::isEmpty( $message_type )){
				if( Util::isEmpty( $store_name )  ) {
					 $errors->addError("store_name is missing");
				}

				if( Util::isEmpty( $store_action )  ) {
					 $errors->addError("store_action is missing. valid action are: push | pop | fetch_all");
				}
				
				if( Util::isEmpty( $store_data )  ) {
				     if(!Util::isEmpty( $store_action ) && !($store_action === "pop" || $store_action === "fetch_all")){
					    $errors->addError("store_data is missing");
					 }
				}

				return false;
			}
			
			if( Util::isEmpty( $message_type ) || !($message_type === "msg_text" || $message_type === "msg_image") ) {
			     if( !($store_action === "pop" || $store_action === "fetch_all")){
				    $errors->addError("message_type is missing or invalid, valid message_type are: msg_text | msg_image");
				 }
			}
 
 			if( $store_action !== "push" && $store_action !== "pop" && $store_action !== "fetch_all" ) {
				 $errors->addError("#store_action is missing. valid action are: push | pop | fetch_all");
                                 return false;
		    }
 
            return true;
        }
       

        public function perform($errors) {
                 $client_key = Util::getJsonParams('client_key');
                 $store_name = Util::getJsonParams('store_name');
                 $store_data = Util::getJsonParams('store_data');
                 $message_type = Util::getJsonParams('message_type');
                                  
                 $store_action = Util::getJsonParams('store_action');
                 $email = parent::getSession()->get('email');

                 if($store_action === "push"){
                    CacheUtil::save($email, $store_name, $message_type, htmlspecialchars($store_data, ENT_QUOTES));
                    return new StoreServiceResponse( $store_name, "---", true);
                 }

                 if($store_action === "pop"){
                    $fetch_data = CacheUtil::fetch($email, $store_name);
                    
                    if(Util::isEmpty($fetch_data)){
                      $errors->addError("store_data not found, store name seems to be invalid");
                      return $errors;
                    }

                    return new StoreServiceResponse( $store_name, $fetch_data, true);
                 }
                 
                 if($store_action === "fetch_all"){
                    $fetch_data = CacheUtil::fetch_all($email);
                    
                    return new StoreServiceResponse( $store_name, $fetch_data, true);
                 }


                 return new StoreServiceResponse( $store_name, "", false);
        }
    }
   
    class StoreServiceResponse {
		public $store_name;
		public $store_data;		
		public $flag;	
		
        public function __construct($store_name, $store_data, $flag){
          $this->store_name = $store_name;		
          $this->store_data = $store_data;
          $this->flag = $flag; 
        }
    }
?>