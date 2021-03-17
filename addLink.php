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

	// $result = $collection->find([ 'hide' => false ]);
	// foreach ($result as $entry) {
	// 	echo $entry['id'], ': ', $entry['url'];
	// }

	// $ip = $_SERVER['REMOTE_ADDR'];

	// $dom = new DOMDocument();
	// $dom->load('./xml/links.xml');
	// $dom->formatOutput = true; 
	// $dom->encoding = 'UTF-8';

	// $r = $dom->documentElement;

	// $c = $dom->createElement('link');
	// $r->appendChild($c);

	// $id = $dom->createElement('id');
	// $id->appendChild($dom->createTextNode(getMaxID()+1));
	// $c->appendChild($id);
	
	// $url = $dom->createElement('url');
	// $url->appendChild($dom->createTextNode($link));
	// $c->appendChild($url);

	// $owner = $dom->createElement('owner');
	// $owner->appendChild($dom->createTextNode($_SERVER['REMOTE_ADDR']));
	// $c->appendChild($owner);

	// $date = $dom->createElement('date');
	// $date->appendChild($dom->createTextNode(date("Y-m-d h:i:sa")));
	// $c->appendChild($date);

	// $star = $dom->createElement('star');
	// $star->appendChild($dom->createTextNode('FALSE'));
	// $c->appendChild($star);

	// $hide = $dom->createElement('hide');
	// $hide->appendChild($dom->createTextNode('FALSE'));
	// $c->appendChild($hide);

	// $dom->saveXML();
	// $dom->save('./xml/links.xml');

	// header("Location: ./index.php#links-title");

	//Find max id and set the id of the new element to max id + 1
	// function getMaxID(){
	// 	$doc = new DOMDocument();
	// 	$doc->load('./xml/links.xml');
	// 	$files = $doc->getElementsByTagName('link');
	// 	if(count($files) == 0){
	// 		return 0;
	// 	}
	// 	else{
	// 		$id = 0;
	// 		foreach($files as $f){
	// 			$thisId = $f->getElementsByTagName('id')->item(0)->nodeValue;
	// 			if($id < $thisId){
	// 				$id = $thisId;
	// 			}
	// 		}
	// 		return $id;
	// 	}
	// }
?>
