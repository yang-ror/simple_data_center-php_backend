<!-- =============================================================================
 addLink.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-10
 last modified: 2021-03-16

 takes data from cookie and insert it to mongodb

 change log:
	2021-03-15:
		1. Optimized for mobile view
	2021-03-16:
		1. Store data in MongoDB instead of xml file
============================================================================= -->
<?php
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	require 'vendor/autoload.php';

	$link = $_POST['nlink'];

	// echo $link;

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->datacenter->links;

	$options = ['sort' => ['id' => -1]];
	$result = $collection->findOne([], $options);
	$maxId = $result['id'];

	// echo $maxId;

	$result = $collection->insertOne( [ 'id' => $maxId + 1, 'title' => $link, 'url' => $link, 'hide' => false ] );

	// echo "Inserted with Object ID '{$result->getInsertedId()}'";

	header("Location: ./index.php#links-title");
?>
