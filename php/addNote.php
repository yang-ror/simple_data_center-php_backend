<!-- =============================================================================
 addNote.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-10
 last modified: 2021-03-18
 
 takes data from post and insert it to json file
============================================================================= -->
<?php
    // ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	$note = $_POST["nnote"];

	// $note = str_replace("<", "&lt;", $note);
	// $note = str_replace(">", "&gt;", $note);

	// echo "<pre>";
	// echo $note;
	// echo "</pre>";

	$jsonPath = '../json/notes.json';
	$jsonFile = file_get_contents($jsonPath);
	$data = json_decode($jsonFile, true);

	$maxId = 0;
	$newData = [];

	if(count($data) != 0){
		foreach($data as $item){
			array_push($newData, $item);
			if($maxId < $item['id']){
				$maxId = $item['id'];
			}
		}
	}

	$id = $maxId + 1;

	$newItem = new \stdClass();
	$newItem->id = $id;
	$newItem->content = $note;
	$newItem->hide = FALSE;

	array_push($newData, $newItem);

	$newJsonFile = json_encode($newData);
	file_put_contents($jsonPath, $newJsonFile);

	header("Location: ../index.php#notes-title");
?>
