<!-- =============================================================================
 mark.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-16
 last modified: 2021-01-15
 
 takes post request as the table name, id, and action; action can be: star, hide, or delete
============================================================================= -->
<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$table = $_GET['t'];
	$id = $_GET['id'];
	$action = $_GET['a'];

	// echo $table;
	// echo $id;
	// echo $action;

	$doc = new DOMDocument();

	if($table == 'links'){
		$doc->load('./xml/links.xml');
		$files = $doc->getElementsByTagName('link');
	}

	else if($table == 'notes'){
		$doc->load('./xml/notes.xml');
		$files = $doc->getElementsByTagName('note');
	}

	// else if($table == 'files'){
	// 	$doc->load('files.xml');
	// 	$files = $doc->getElementsByTagName('file');
	// }

	$r = $doc->documentElement;

	if(count($files) > 0){
		foreach($files as $f){
			$itemid = $f->getElementsByTagName('id')->item(0)->nodeValue;
			$star = $f->getElementsByTagName('star')->item(0)->nodeValue;
			$hide = $f->getElementsByTagName('hide')->item(0)->nodeValue;

			if($id == $itemid){
				if($action == 'star'){
					if($star == 'FALSE'){
						$f->getElementsByTagName('star')->item(0)->nodeValue = 'TRUE';
					}
					else if($star == 'TRUE'){
						$f->getElementsByTagName('star')->item(0)->nodeValue = 'FALSE';
					}
				}
				else if($action == 'delete'){
					// if($hide == 'FALSE'){
					// 	$f->getElementsByTagName('hide')->item(0)->nodeValue = 'TRUE';
					// }
					// else if($hide == 'TRUE'){
						$r->removeChild($f);
						if($table == 'files'){
							unlink('files/' . $name = $f->getElementsByTagName('name')->item(0)->nodeValue);
						}
					// }
				}
				else if($action == 'recover'){
					$f->getElementsByTagName('hide')->item(0)->nodeValue = 'FALSE';
				}
				break;
			}
		}
	}

	if($table == 'links'){
		$doc->saveXML();
		$doc->save('./xml/links.xml');
		header("Location: ./index.php#links-title");
	}

	if($table == 'notes'){
		$doc->saveXML();
		$doc->save('./xml/notes.xml');
		header("Location: ./index.php#notes-title");
	}

	// if($table == 'files'){
	// 	$doc->saveXML();
	// 	$doc->save('files.xml');
	// }
?>