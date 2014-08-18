<?php
    class DbConnection {
        private $connection; 

        function __construct(){
              $this->connection = MySQLConnectionFactory::create();
			  
              if (!$this->connection) {
                 die('Could not connect: ' . mysql_error());
              }
        }

        public function getConnection() {
             return $this->connection;
        }

        public function query($sql){
             $result = mysql_query($sql, $this->getConnection());
			 
             if (!$result) {
                echo "DB Error, could not query the database\n";
                echo 'MySQL Error: ' . mysql_error();
                exit;
             }
			 return $result;
        }
    }

    class MySQLConnectionFactory {
	    static $SERVERS = array(
		    array(
		        'host' => 'xxx',
		        'username' => 'xxx',
		        'password' => 'xxx',
		        'database' => 'xxx'),
	    );
	
	    public static function create() {
		    // Figure out which connections are open, automatically opening any connections
		    // which are failed or not yet opened but can be (re)established.
		    $cons = array();
		    for ($i = 0, $n = count(MySQLConnectionFactory::$SERVERS); $i < $n; $i++) {
		        $server = MySQLConnectionFactory::$SERVERS[$i];
		        $con = mysql_pconnect($server['host'], $server['username'], $server['password']);
		        if (!($con === false)) {
		        if (mysql_select_db($server['database'], $con) === false) {
		            echo('Could not select database: ' . mysql_error());
		            continue;
		        }
		        $cons[] = $con;
		        }
		    }
		    // If no servers are responding, throw an exception.
		    if (count($cons) == 0) {
		        throw new Exception
		        ('Unable to connect to any database servers - last error: ' . mysql_error());
		    }
		    // Pick a random connection from the list of live connections.
		    $serverIdx = rand(0, count($cons)-1);
		    $con = $cons[$serverIdx];

		    // Return the connection.
		    return $con;
	    }
    }

    class DbProvider {
        public $connection;
       
        public function __construct(){
            $this->connection = new DbConnection();
        }

        public function getAllUsers(){
            $sql    = 'SELECT email FROM juser  WHERE 1';
            $result =  $this->connection->query( $sql );
 
             $stack = array();
             while ($row = mysql_fetch_assoc($result)) {
                    array_push($stack,  $row['email'] );
             }
             
             return $stack;//implode(",",  $stack) ;
        }


        public function getUser($email){
            $sql    = "SELECT email, password FROM juser  WHERE email='".$email."'" ;
            $result =  $this->connection->query( $sql );
 
             $stack = array();
             while ($row = mysql_fetch_assoc($result)) {
                    $user_info = new UserInfo("", "", "", "", "", $row['email'], $row['password'] );
                    return  $user_info;
             }
             
             return null; // $stack;//implode(",",  $stack) ;
        }

		 
        public function createUser($first_name, $last_name, $city, $country, 
									$phone, $email, $password){
            $zip = "10603";
            $sql0    = "Insert into users (username, password) values ('". $email ."','". $password ."')";
            $sql = "insert into juser (email,password,first_name,last_name,phone,city,country,zip) values ('". $email ."','". $password ."','". $first_name. "','". $last_name ."','". $phone ."','". $city ."','". $country ."','". $zip ."')";

            if($this->connection->query( $sql )){
                return true;
            }

	    return false;

	    //$result =  $this->getAllUsers();
            //$stack = array();
			
            //while ($row = mysql_fetch_assoc($result)) {
            //    array_push($stack,  $row['username'] );
            //}
             
            //return $stack;//implode(",",  $stack) ;
        }
    }
?>