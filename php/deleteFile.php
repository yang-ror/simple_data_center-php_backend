<!-- =============================================================================
 deleteFile.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-12
 last modified: 2021-01-15
 
 takes post request as the filename and delete a file with the name
============================================================================= -->
<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	function isImage($file){
		$endOfName = 0;
		for($i = strlen($file)-1; $i > 0; $i--){
			if($file[$i] == "."){
				$endOfName = $i+1;
				break;
			}
		}
		$fileType = substr($file, $endOfName);
		$types = ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF","PNG"];
		return in_array($fileType, $types);
	}

	$filename = $_GET['fname'];
    $pathToFile = '../files/' . $filename;

	// echo $pathToFile;

	unlink($pathToFile);

	// $command = "rm " . $pathToFile;
	// exec($command, $output, $retval);
	
	if(isImage($filename)){
		header("Location: ../index.php#images-title");
	}
	else{
		header("Location: ../index.php#files-title");
	}
?>