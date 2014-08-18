<?php
   if ($_SERVER['REQUEST_METHOD'] === 'POST') { //start if-post block
	   $ip = $_SERVER['REMOTE_ADDR'];
 
	   $authParam = isset($_POST[auth_code]) ? $_POST[auth_code] : '';
	   
	   $adminNotify = isset($_POST[notify]) ? $_POST[notify] : 'true';
	   
	   if(strcasecmp( $authParam, 'rkonlineauth' ) == 0 || true){
			   $fileName = $_FILES["ufile"]["name"]; 
			   $fileTmpLoc = $_FILES["ufile"]["tmp_name"];
			   
			   $pathAndName = "/hermes/bosweb25c/b1402/ipw.javabrowncom/jBrownCms/jws/transaction-data/uploads/".$fileName;
			   $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
			   
			   if ($moveResult == true) {
				   $msg = "Uploaded File from JWS-> ".$fileName;
				   
				   if($adminNotify == 'true'){
				     mail("getrk@yahoo.com","File Uploaded to Your Server",  $ip,  "javaborwn upload.<br>".msg);
				   }
				   
				   echo "file uploaded success: ".$fileName;
				   //header("Upload=TRUE");
			   } else {
				 // echo "<h1><center>ERROR: File not moved correctly, (permission denied)</center></h1>";
				 header("Location: upload-error.php?error=".$fileName);
				   //header("Upload=FALSE");
				  echo "file uploaded failed: ".$fileName;
			   }
		}
		else{
		  echo "Authorization failed to use this service";
		  header("Location: upload-error.php?error=".$fileName."&service-auth=(fail)=>".$authParam);
		}
   }
?>