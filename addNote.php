<!-- =============================================================================
 addNote.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-10
 last modified: 2021-01-15
 
 takes data from cookie and insert it to mongodb
============================================================================= -->
<?php
    // ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

	require 'vendor/autoload.php';

	$note = $_COOKIE["note"];

	// echo $note;

	$note = str_replace("\\", "{bs}", $note);

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$collection = $client->datacenter->notes;

	$options = ['sort' => ['id' => -1]];
	$result = $collection->findOne([], $options);
	$maxId = $result['id'];

	// echo $maxId;

	$result = $collection->insertOne( [ 'id' => $maxId + 1, 'content' => $note, 'hide' => false ] );

	// echo "Inserted with Object ID '{$result->getInsertedId()}'";

	header("Location: ./index.php#notes-title");




	// $ip = $_SERVER['REMOTE_ADDR'];

	// $dom = new DOMDocument();
	// $dom->load('./xml/notes.xml');
	// $dom->formatOutput = true; 
	// $dom->encoding = 'UTF-8';

	// $r = $dom->documentElement;

	// $c = $dom->createElement('note');
	// $r->appendChild($c);

	// $id = $dom->createElement('id');
	// $id->appendChild($dom->createTextNode(getMaxID()+1));
	// $c->appendChild($id);
	
	// $content = $dom->createElement('content');
	// $content->appendChild($dom->createTextNode($note));
	// $c->appendChild($content);

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
	// $dom->save('./xml/notes.xml');

	// header("Location: ./index.php#notes-title");

	// //Find max id and set the id of the new element to max id + 1
	// function getMaxID(){
	// 	$doc = new DOMDocument();
	// 	$doc->load('./xml/notes.xml');
	// 	$files = $doc->getElementsByTagName('note');
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
