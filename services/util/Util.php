<?php
    class Util {
        public static function getParams($paramName){
            if(isset($_POST[$paramName])){
                return htmlentities ( $_POST[$paramName] );
            }
           
            if(isset($_GET[$paramName])){
                return  htmlentities ( $_GET[$paramName] );
            }
           
            return '';
        }

        public static function getJsonParams($paramName){
            $value = Util::getParams($paramName);
        	
            if( !Util::isEmpty( $value )  ){
                return $value;
            }
           
            $json = file_get_contents("php://input");
            $data = json_decode($json, true);
            
            if( isset($data) &&  isset( $data[$paramName]  ) ){
                 return $data[$paramName] ;
            }

            return '';
        }

        public static function is_invalid_post_json(){
            $json = file_get_contents("php://input");

            if($_SERVER['REQUEST_METHOD'] === 'POST' && json_decode($json) === null) {
              return true;
            }

            return false;
        }

        public static function objectToXml($obj){
            //return XMLSerializer::generateValidXmlFromMixiedObj(Util::getParsableData($obj));
            return XMLSerializer::toXml($obj);
        }
       
        public static function toJson($obj){
            return json_encode(Util::getParsableData($obj));
        }
       
        public static function isEmpty($str) {
            return (!isset($str) || trim($str)==='');
        }
       
        public static function  getParsableData($obj){
            $var = get_object_vars($obj);
            foreach($var as &$value){
              if(is_object($value)){
                  $value = Parser::getParsableData($value);//->getParsableData();
              }
            }
            return $var;
        }

        public static function encrypt($string) {
			$key = "ni786sha";
			$encrypted = 
			  base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
            return $encrypted;
        }
		
	public static function decrypt($string) {
			$key = "ni786sha";
			$decrypted = 
			  rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
            return $decrypted;
        }

       public static  function is_authenticated($session){
			$email = $session->get('email'); 
            $internal_key = Util::encrypt($email); 
            $is_logged_in = $session->get('active');
			
			$client_key = Util::getJsonParams('client_key');
			
			if(!Util::isEmpty($is_logged_in) && $is_logged_in == true && !Util::isEmpty($internal_key) && $internal_key === $client_key){
				return true;
			}
			
			return false;
        }
    }
?>