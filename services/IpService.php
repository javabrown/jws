<?php
    class IpService extends Service {
        public function validate($errors){
            return true;
        }
       
        public function perform($errors) {
            return new IpServiceResponse();
        }
    }
   
    class IpServiceResponse {
        public $external_ip;
        public $request_ip;
       
        public function __construct(){
          $this->external_ip = $_SERVER['REMOTE_ADDR'];
          $this->request_ip = "--";//$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
?>
