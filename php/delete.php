<!-- =============================================================================
 delete.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2021-03-18
 
 delete link or note by id
============================================================================= -->
<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

    $deleteFrom = $_GET['from'];
    $id = $_GET['id'];

    // echo $id;

	if($deleteFrom == 'links'){
    	$jsonPath = '../json/links.json';
	}
	else if($deleteFrom == 'notes'){
		$jsonPath = '../json/notes.json';
	}
	
	$jsonFile = file_get_contents($jsonPath);
	$data = json_decode($jsonFile, true);

	$newData = [];

	if(count($data) != 0){
		foreach($data as $item){
            if($id != $item['id']){
			    array_push($newData, $item);
			}
		}
	}

	$newJsonFile = json_encode($newData);
	file_put_contents($jsonPath, $newJsonFile);

    header("Location: ../index.php#links-title");
?>