<?php
    class UploadService extends Service {

        public function validate($errors){
	        $client_key = Util::getJsonParams('client_key');
	        $store_name = Util::getJsonParams('store_name');
                $store_data = Util::getJsonParams('store_data');
                $store_action = Util::getJsonParams('store_action');
               $message_type = Util::getJsonParams('message_type');


        ob_start();
        var_dump( $serviceName);
        var_dump(  $client_key );
       var_dump(  $store_action );
        var_dump(  $errors );
	$log  = ob_get_clean();
        error_log( "JWS==> STORAGE-VALIDATION1". $_SERVER['REQUEST_METHOD'] ."===[". $log ." ]");

			if(Util::isEmpty( $client_key ) ){
			   $errors->addError("missing client_key");
			}
			
			if( Util::isEmpty( $store_name ) || Util::isEmpty( $store_data ) || Util::isEmpty( $store_action )){
				if( Util::isEmpty( $store_name )  ) {
					 $errors->addError("store_name is missing");
				}

				if( Util::isEmpty( $store_action )  ) {
					 $errors->addError("store_action is missing. valid action are: push | pop | fetch_all");
				}
				
				if( Util::isEmpty( $store_data )  ) {
					    $errors->addError("store_data is missing");
				}
				
				if( Util::isEmpty( $message_type ) || !($message_type === "msg_image") ) {
					    $errors->addError("message_type is missing. valid message_type are: msg_image");
				}

				return false;
			}
 
 			if( $store_action !== "push" && $store_action !== "push_all") {
				 $errors->addError("#store_action is missing. valid action are: push | push_all");
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

                 error_log( "STORAGE perform called - ".$store_action);

                 if($store_action === "push"){
                    error_log( "JWS-StorageService ==> PUSH STARTED ".  $store_name);

                    CacheUtil::saveToTemp($client_key, $store_name, $message_type, htmlspecialchars($store_data, ENT_QUOTES));

                    error_log( "JWS-StorageService ==> PUSH ENDED ".  $store_name);
                    return new StoreServiceResponse( $store_name, "---", true);
                 }

 

                 return new StoreServiceResponse( $store_name, "", false);
        }
    }
   
    class UploadServiceResponse {
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