<?php
class CacheUtil{
    public static $root_dir = "/hermes/bosweb25c/b1402/ipw.javabrowncom/jBrownCms/jws/transaction-data/users/";
   public static $temp_upload_dir = "/hermes/bosweb25c/b1402/ipw.javabrowncom/jBrownCms/jws/transaction-data/uploads/";
 
	public static function saveUploadsToUserDir($client_key, $user_email, $cache_name, $msg_type){
	   $serialzed_client_key = str_replace(array(' ', '<', '>', '&', '+', '=', '{', '}', '*','@','.'), array('-'),  $client_key);
	   $serialzed_user_email = str_replace(array(' ', '<', '>', '&', '{', '}', '*','@','.'), array('-'),  $user_email);
	   
 	   $from_file_path = CacheUtil::$temp_upload_dir. "/" . $serialzed_client_key .'.dat';
	   
	   $to_dir =  $user_dir = CacheUtil::$root_dir. $serialzed_user_email;
	   
	   $to_file_path =  $to_dir."/".$cache_name.'.dat';

	   
	   if(!file_exists( $to_dir ) || !is_dir( $to_dir )){
		 mkdir( $to_dir ); 
	   }	 
	   
	   if (!copy($from_file_path, $to_file_path)) {
	       error_log( "JWS==> FILEED TO SAVE TO  ".  $to_file_path );
	   }
	   else{
	      error_log( "JWS==> SUCCESS!! OLD COPY IS GETTING DELETED NOW");
	      unlink($from_file_path);
		  error_log( "JWS==> SUCCESS!! OLD COPY IS DELETED!!");
	   }
	    error_log( "JWS==> SUCCESS CALL FOR saveUploadsToUserDir - ENDDED!!");
	}

    public static function saveToTemp($client_key, $cache_name, $msg_type, $data){
       $serialzed_client_key = str_replace(array(' ', '<', '>', '&', '+', '=', '{', '}', '*','@','.'), array('-'),  $client_key);

       $file_path = CacheUtil::$temp_upload_dir. "/" . $serialzed_client_key .'.dat';
	    
       $data_with_meta = array('msg_type'=>$msg_type, 'message'=> $data);
        file_put_contents($file_path, serialize($data_with_meta));
		
	error_log( "JWS==> DATA SAVED TO TEMP DIR= ".  $file_path );
		
        //$array = unserialize(file_get_contents('foo.txt')));

	    //file_put_contents($file_path, $data);
    }


    public static function save($user_email, $cache_name, $msg_type, $data){
       $serialzed_user_email = str_replace(array(' ', '<', '>', '&', '{', '}', '*','@','.'), array('-'),  $user_email);
       $user_dir = CacheUtil::$root_dir. $serialzed_user_email;
       error_log( "JWS==> SAVE Started .... - DATA = ". serialize($data_with_meta));
	   if(!file_exists( $user_dir ) || !is_dir( $user_dir )){
		 mkdir( $user_dir ); 
	   }

	    $file_path = $user_dir."/".$cache_name.'.dat';
	    
	    $data_with_meta = array('msg_type'=>$msg_type, 'message'=> $data);
        file_put_contents($file_path, serialize($data_with_meta));
		
		error_log( "JWS==> DATA SAVED TO FILE - DATA = ". serialize($data_with_meta));
		
        //$array = unserialize(file_get_contents('foo.txt')));

	    //file_put_contents($file_path, $data);
    }

    public static function fetch($user_email, $cache_name){
        $serialzed_user_email = str_replace(array(' ', '<', '>', '&', '{', '}', '*','@','.'), array('-'),  $user_email);
  
	    $file_path = CacheUtil::$root_dir.$serialzed_user_email ."/".$cache_name.'.dat';
	    //return file_get_contents($file_path);
	    return unserialize(file_get_contents($file_path));
    }

    public static function fetch_all($user_email){
         $serialzed_user_email = str_replace(array(' ', '<', '>', '&', '{', '}', '*','@','.'), array('-'),  $user_email);
         $user_dir = CacheUtil::$root_dir.$serialzed_user_email;
         $result = array();
         
         if ($handle = opendir($user_dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    //echo "$entry\n";
                    $result[$entry]= unserialize( file_get_contents($user_dir."/".$entry) ) ;
                }
            }
            closedir($handle);
         }
         return $result;
    }

    function add_to_array($array, $key, $value) {
		if(array_key_exists($key, $array)) {
			if(is_array($array[$key])) {
				$array[$key][] = $value;
			}
			else {
				$array[$key] = array($array[$key], $value);           
			}
		}
		else {
			$array[$key] = array($value);
		}
    }
    
    /*public static save_array($file_name, $array){
        $toBeSaved = serialize($array);
        file_put_contents($file_name, $toBeSaved);
    }
    
    public static get_array($file_name){
        $toBeRestored = file_get_contents($file_name);
        $data = unserialize($toBeRestored);
        return $data;
    }*/
}

/*
abstract class Message{
 const MSG_TYPE_TEXT = 0;
 const MSG_TYPE_IMAGE = 1;
 const MSG_TYPE_AUDIO = 2;
 private $from_user;
 private $to_user;
 
 abstract function push();
 abstract function pop();
 abstract function type();
}

class TextMessage extends Message {
    function push(){
 
      file_put_contents("/hermes/bosweb25c/b1402/ipw.javabrowncom/downloads/user/services/pipe/transactions/stream-".$ip.".txt", $image_data);
    }
    
    function pop(){
      
    }
    
    function type(){
      return MSG_TYPE_TEXT;
    }
}*/

?>