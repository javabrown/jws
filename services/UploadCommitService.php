<?php
    class UploadCommitService extends Service {

        public function validate($errors){
	        $client_key = Util::getJsonParams('client_key');
	        $store_name = Util::getJsonParams('store_name');
                $store_data = Util::getJsonParams('store_data');
                $store_action = Util::getJsonParams('store_action');
               $message_type = Util::getJsonParams('message_type');
 
 error_log( "JWS-UploadCommitService ==> VALIDATION-1 ".   $client_key. ", " . $store_action);

			if(Util::isEmpty( $client_key ) || !Util::is_authenticated( parent::getSession() )){
			  $errors->addError("missing client_key or invalid authentication");
			}
			
			
			if( Util::isEmpty( $store_name ) || Util::isEmpty( $store_data ) || Util::isEmpty( $store_action )){
				if( Util::isEmpty( $store_name )  ) {
					 $errors->addError("store_name is missing");
				}

				if( Util::isEmpty( $store_action )  ) {
					 $errors->addError("store_action is missing. valid action are: push");
				}
				
				//if( Util::isEmpty( $store_data )  ) {
				//	    $errors->addError("store_data is missing");
				//}
				
				if( Util::isEmpty( $message_type ) || !($message_type === "msg_image") ) {
					    $errors->addError("message_type is missing. valid message_type are: msg_image");
				}
       ob_start();       
 var_dump(  $errors );
        error_log( "JWS==> STORAGE-VALIDATION1". $_SERVER['REQUEST_METHOD'] ."===[". ob_get_clean() ." ]");
 

				return false;
			}
 
 			if( $store_action !== "push" && $store_action !== "push_all") {
				 $errors->addError("#store_action is missing. valid action are: push | push_all");
       ob_start();       
 var_dump(  $errors );
        error_log( "JWS==> STORAGE-VALIDATION1". $_SERVER['REQUEST_METHOD'] ."===[". ob_get_clean() ." ]");
                                 return false;
		       }
			
			return true;
 
        }

        public function perform($errors) {
 
                 $client_key = Util::getJsonParams('client_key');
                 $store_name = Util::getJsonParams('store_name');
                 //$store_data = Util::getJsonParams('store_data');
                 $message_type = Util::getJsonParams('message_type');
                 $email = parent::getSession()->get('email');
                 
                 $store_action = Util::getJsonParams('store_action');
  error_log( "JWS-UploadCommitService ==> in Perform ".  $store_name);

                 if($store_action === "push"){
                    error_log( "JWS-StorageService ==> PUSH-COMMIT STARTED ".  $store_name);

                    CacheUtil::saveUploadsToUserDir($client_key, $email , $store_name, $message_type);

                    error_log( "JWS-UploadCommitService ==> PUSH-COMMIT ENDED ".  $store_name);
                    return new UploadCommitServiceResponse ( $store_name, "---", true);
                 }

 

                 return new StoreServiceResponse( $store_name, "", false);
        }
    }
   
    class UploadCommitServiceResponse {
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