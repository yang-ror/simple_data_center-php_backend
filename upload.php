<!-- =============================================================================
 upload.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-17
 last modified: 2021-01-15
 
 takes post request as the file, and upload it to ./files/
============================================================================= -->
<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$dir = "./files/";
	$file = $dir . $_FILES["nfile"]["name"];
	
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

	if(file_exists($file)){

		$endOfName = 0;

		for($i = 5; $i < strlen($file); $i++){

			if($file[$i] == "."){
				$endOfName = $i;
				//break;
			}
		}

		$file = substr_replace($file, "(2)", $endOfName, 0);
		$j = 3;
		while(file_exists($file)){
			$file[$endOfName + 1] = $j;
			$j++;
		}
	}

	if($uploadOk == 1){
		if(move_uploaded_file($_FILES["nfile"]["tmp_name"], $file)){
			echo "Success";
		}
		else{
			echo "Error code: ".$_FILES["nfile"]["error"];
		}
	}
	
	header("Location: ./index.php");
?>
