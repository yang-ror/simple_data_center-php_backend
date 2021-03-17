<!-- =============================================================================
 deleteNote.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2021-03-16
 last modified: 2021-03-16

 delete note by id
============================================================================= -->
<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require 'vendor/autoload.php';

    $id = $_GET['id'];

    // echo $id;

    $client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->datacenter->notes;

    $result = $collection->deleteOne( [ 'id' => $id+""] );

    printf("Deleted %d document(s)\n", $result->getDeletedCount());

    header("Location: ./index.php#notes-title");
?>