<?php
    include 'services/util/Util.php';
    include 'services/util/CacheUtil.php';
    include 'services/util/Errors.php';
    include 'services/util/Session.php';
    include 'services/model/UserInfo.php';
    include 'services/providers/DbProvider.php';
 
    include 'services/Service.php';
    include 'services/EchoServiceResponse.php';
    include 'services/IpService.php';
    include 'services/AuthService.php';
    include 'services/CreateUserService.php';
    include 'services/SignoutService.php';
    include 'services/StorageService.php';
    include 'services/UploadService.php';
    include 'services/UploadCommitService.php';

    $serviceName = Util::getJsonParams('service');
    $callback = Util::getJsonParams('callback');
    $format = Util::getJsonParams('format');
 
	 
        ob_start();
        var_dump( $serviceName);
        var_dump( Util::getJsonParams('store_action') );
	$log  = ob_get_clean();
        error_log( "JWS==> ". $_SERVER['REQUEST_METHOD'] ."===[". $log ." ]");
	 
    $factory = new ServiceFactory();
    $serviceResponse = $factory->create($serviceName);
    $output = new Output($serviceResponse, $format, $callback);
    $output->echoResponse();
?>

<?php

    abstract class ServiceDirectory{

        public function getServiceList(){
			$serviceList = array(
				"EchoService", //=> "Test Service User",
				"IpService", //=> "Return External Ip Address of Client",
				"AuthService", //=> "Return External Ip Address of Client"
				"CreateUserService", //=> "Create User from Posted Input"
				"SignoutService", //=> "Create User from Posted Input"
				"StorageService", //=> "Create User from Posted Input"
				"UploadService", //=> "Create User from Posted Input"
				"UploadCommitService", //=> "Create User from Posted Input"
			);
			
			return $serviceList;
        }

        public  function getService($serviceName){
             if ($serviceName!= null && $serviceName!= '') {
                $serviceName = strtolower($serviceName);
             }

            switch($serviceName){
                case "authservice":
                    return new AuthService();
                    break;
                case "echoservice":
                	return new EchoService();
                	break;
                case "ipservice":
                	return new IpService();
                	break;
                case "createuserservice":
                	return new CreateUserService();
                	break;
                case "signoutservice":
                	return new SignoutService();
                	break;
                case "storageservice":
                	return new StorageService();
                	break;
                case "uploadservice":
                	return new UploadService();
                	break;
                case "uploadcommitservice":
                	return new UploadCommitService();
                	break;
            }
            return null;
        }
    }

    class ServiceFactory  extends ServiceDirectory {
        const USER_SERVICE = "UserService";
        private $err;
        
        public function __construct(){
          $this->err = new Errors();
        }
        
        public function create($serviceName){
            $service = $this->getService($serviceName);

            if ($service === null) {
			    if (Util::is_invalid_post_json()){
				  $this->err->addError("Input POST request, pls validate your JSON Input");
				  return $this->err;
				}
                $this->err->addError("Service not found");
				$this->err->addError("Valid services are:". implode(",", $this->getServiceList()) );
				return $this->err;
			}

			//apply validation from individual service
            $service->validate($this->err);
			
            if($this->err->hasError()){
                return $this->err;
            }
            
            return $service->perform($this->err);
        }
    }
?>


<?php
 /*   class Errors  {
        public $errors;
       
        public function __construct(){
          $this->errors = array();
        }
       
        public function addError($errorMsg){
            array_push($this->errors, $errorMsg);
        }
        
        public function getErrors(){
        	return serialize($this->errors);
        }
        
        public function hasError(){
            if (sizeof($this->errors) > 0) {
                return true;
            }
            return false;
        }
    } */
?>


<?php
    class Output {
        protected $outputObject;
        protected $format;
        protected $callback;
       
        public function __construct($outputObject, $format, $callback){
          $this->outputObject = $outputObject;
          $this->format = $format;
          $this->callback = $callback;
          if($this->format === ''){
            $this->format = 'json';
          }
        }
       
        public function echoResponse(){
            header('Content-Type: application/'.$this->format);
            header("Access-Control-Allow-Origin", "*");
           
            if($this->callback === ''){
                echo $this->getResponse();
            }
            else{
                echo $this->callback."(".$this->getResponse().")";
            }
        }
       
        public function getResponse(){
            if($this->format === 'xml'){
                return $this->getXmlOutput();
            }
           
            if($this->format === 'json'){
              return $this->getJsonOutput();
            }
        }
       
        private function getXmlOutput(){
                return Util::objectToXml($this->outputObject);
        }
       
        private function getJsonOutput(){
                return Util::toJson($this->outputObject);
        }
       
        function getParsableData(){
            $var = get_object_vars($this->outputObject);
            foreach($var as &$value){
              if(is_object($value) && method_exists($value,'getParsableData')){
                  $value = $value->getParsableData();
              }
            }
            return $var;
        }
    }
?>


<?php
    /*class Util {
        public static function getParams($paramName){
            if(isset($_POST[$paramName])){
                return $_POST[$paramName];
            }
           
            if(isset($_GET[$paramName])){
                return $_GET[$paramName];
            }
           
            return '';
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
    }*/
?>

<?php
    class XMLSerializer {
        public static function toXml($obj) {
            $json = Util::toJson($obj);
            $array = json_decode($json, true);
           
            return XMLSerializer::arrayToXml($array, '<response></response>');
        }
   
        private static function arrayToXml($array, $rootElement = null, $xml = null) {
            $_xml = $xml;

            if ($_xml === null) {
                $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root><root/>');
            }

            foreach ($array as $k => $v) {
                if (is_array($v)) { //nested array
                    XMLSerializer::arrayToXml($v, $k, $_xml->addChild($k));
                }
                else {
                    $_xml->addChild($k, $v);
                }
            }
            return $_xml->asXML();
        }
   
        /**
        *
        * The most advanced method of serialization.
        *
        * @param mixed $obj => can be an objectm, an array or string. may contain unlimited number of subobjects and subarrays
        * @param string $wrapper => main wrapper for the xml
        * @param array (key=>value) $replacements => an array with variable and object name replacements
        * @param boolean $add_header => whether to add header to the xml string
        * @param array (key=>value) $header_params => array with additional xml tag params
        * @param string $node_name => tag name in case of numeric array key
        */
        public static function generateValidXmlFromMixiedObj($obj, $wrapper = null, $replacements=array(), $add_header = true, $header_params=array(), $node_name = 'node')
        {
            $xml = '';
            if($add_header)
                $xml .= self::generateHeader($header_params);
            if($wrapper!=null) $xml .= '<' . $wrapper . '>';
            if(is_object($obj))
            {
                $node_block = strtolower(get_class($obj));
                if(isset($replacements[$node_block])) $node_block = $replacements[$node_block];
                $xml .= '<' . $node_block . '>';
                $vars = get_object_vars($obj);
                if(!empty($vars))
                {
                    foreach($vars as $var_id => $var)
                    {
                        if(isset($replacements[$var_id])) $var_id = $replacements[$var_id];
                        $xml .= '<' . $var_id . '>';
                        $xml .= self::generateValidXmlFromMixiedObj($var, null, $replacements,  false, null, $node_name);
                        $xml .= '</' . $var_id . '>';
                    }
                }
                $xml .= '</' . $node_block . '>';
            }
            else if(is_array($obj))
            {
                foreach($obj as $var_id => $var)
                {
                    if(!is_object($var))
                    {
                        if (is_numeric($var_id))
                            $var_id = $node_name;
                        if(isset($replacements[$var_id])) $var_id = $replacements[$var_id];
                        $xml .= '<' . $var_id . '>';   
                    } 
                    $xml .= self::generateValidXmlFromMixiedObj($var, null, $replacements,  false, null, $node_name);
                    if(!is_object($var))
                        $xml .= '</' . $var_id . '>';
                }
            }
            else
            {
                $xml .= htmlspecialchars($obj, ENT_QUOTES);
            }

            if($wrapper!=null) $xml .= '</' . $wrapper . '>';

            return $xml;
        } 

        /**
        *
        * xml header generator
        * @param array $params
        */
        public static function generateHeader($params = array())
        {
            $basic_params = array('version' => '"1.0"', 'encoding' => '"UTF-8"');
            if(!empty($params))
                $basic_params = array_merge($basic_params,$params);

            $header = '<?xml';
            foreach($basic_params as $k=>$v)
            {
                $header .= ' '.$k.'='.$v;
            }
            $header .= ' ?>';
            return $header;
        }   

    }
?>