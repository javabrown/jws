<?php
class CacheUtil{
    public static $root_dir = "/hermes/bosweb25c/b1402/ipw.javabrowncom/jBrownCms/jws/transaction-data/users/";
	
    public static function save($user_id, $cache_name, $data){
       error_log( "JWS-SAVE==> ". $cache_name ."===[". $data ." ] BEGIN");

       //file_put_contents($cache_name.'.txt', $data);
           $user_id1 = Util::decrypt($user_id);        echo "->".$user_id1."<-";
           $user0 = "my-dir";str_replace(array(' ', '<', '>', '&', '{', '}', '*','@','.'), array('-'),  $user_id1);
 echo CacheUtil::$root_dir.$user0;
	   if(!file_exists( CacheUtil::$root_dir.$user0 ) || !is_dir( CacheUtil::$root_dir.$user0 )){
		 mkdir( $root_dir.$user0 ); 
	   }

	    $file_path = CacheUtil::$root_dir.$user_id."/".$cache_name.'.dat';
	    file_put_contents($file_path, $data);
            error_log( "JWS-SAVE==> ". $cache_name ."===[". $file_path ." ] END");
    }

    public static function fetch($user_id, $cache_name){
       //return file_get_contents($cache_name.'.txt');
           $user_id1 = Util::decrypt($user_id);
           $user0 = str_replace(array(' ', '<', '>', '&', '{', '}', '*','@','.'), array('-'),  $user_id1);
  
	    $file_path = CacheUtil::$root_dir.$user0 ."/".$cache_name.'.dat';
	    return file_get_contents($file_path);
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