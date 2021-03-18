<!-- =============================================================================
 addNote.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-10
 last modified: 2021-01-15
 
 takes data from post and insert it to mongodb
============================================================================= -->
<?php
    ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require 'vendor/autoload.php';

	$note = $_POST["nnote"];

	// $note = str_replace("<", "&lt;", $note);
	// $note = str_replace(">", "&gt;", $note);

	// echo "<pre>";
	// echo $note;
	// echo "</pre>";

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->datacenter->notes;

	$options = ['sort' => ['id' => -1]];
	$result = $collection->findOne([], $options);
	$maxId = $result['id'];

	// echo $maxId;

	$result = $collection->insertOne( [ 'id' => $maxId + 1, 'content' => $note, 'hide' => false ] );

	echo "Inserted with Object ID '{$result->getInsertedId()}'";

	header("Location: ./index.php#notes-title");
?>
