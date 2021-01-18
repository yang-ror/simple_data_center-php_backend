<!-- =============================================================================
 index.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-09
 last modified: 2021-01-15
============================================================================= -->

<!doctype html>
<html lang="en">
    <head>
		<title>Simple Data Center</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0", charset=utf-8>

		<link rel="stylesheet" type="text/css" href="bootstrap-5.0.0-beta1-dist/css/bootstrap.min.css">
        <script src = "bootstrap-5.0.0-beta1-dist/js/bootstrap.js"></script>

		<style>
			.image-previews{
				max-width: 100%;
			}
			.images{
				/* To make sure 4x3 picture shows in full */
				max-height: 488px;
				overflow: hidden;
			}
			.image-title{
				max-width: 200px;
				vertical-align:top;
				display: inline-block;
			}
			.notes{
				max-width: 18rem;
				height: 18rem;
			}
			.note-body{
				overflow-y: auto;
			}
		</style>

		<script>
			//delete file by the filename
			function deleteFile(filename){
				window.location.replace("./deleteFile.php?fname=" + filename);
			}

			//Before sending data to php, change all special characters to plain text in its {shorthand} for better storage
			function saveNote(){
				var note = document.getElementById('nnote').value;
				note = note.replace(new RegExp('\r?\n', 'g'), '{nl}');
				note = note.replace('\t', '{tb}');
				note = note.replace('"', '{dq}');
				note = note.replace('\;', '{sc}');
				note = note.replace("'", "{sq}");
				document.cookie = "note=" + note;
				window.location.replace("./addNote.php");
			}

			//select all the text in a box to copied by the user
			function selectAll(element){
				var doc = document, text = doc.getElementById(element), range, selection;    
				if(doc.body.createTextRange){
					range = document.body.createTextRange();
					range.moveToElementText(text);
					range.select();
				}
				else if(window.getSelection){
					selection = window.getSelection();        
					range = document.createRange();
					range.selectNodeContents(text);
					selection.removeAllRanges();
					selection.addRange(range);
				}
			}

			//delete note by its id
			function deleteNote(id){
				window.location.replace("./mark.php?t=notes&id=" + id + "&a=delete");
			}
		</script>
    </head>

    <body>
		<div class="container">
			<h1>Simple Data Center</h1>
			
			<div class="btn-group" role="group" aria-label="Basic example">
				<button type="button" class="btn btn-primary" onclick="location.href='#files-title';">Files</button>
				<button type="button" class="btn btn-primary" onclick="location.href='#images-title';">Images</button>
				<button type="button" class="btn btn-primary" onclick="location.href='#links-title';">Links</button>
				<button type="button" class="btn btn-primary" onclick="location.href='#notes-title';">Notes</button>
			</div>

			<br>
			<br>
			<h2 id="files-title">Files</h2>

			<form action="./upload.php" method ="post" enctype="multipart/form-data">
				<div class="input-group mb-3">
					<input type="file" class="form-control" id="inputGroupFile02" name="nfile">
					<input class="input-group-text btn btn-outline-primary" for="inputGroupFile02" type="submit" value = "Upload"></input>
				</div>
			</form>

			<!-- <form action="upload.php" method ="post" enctype="multipart/form-data">
				<input type="file" id="nfile" name="nfile">
				<input type="submit" value = "Upload" name ="submit">
			</form> -->

			<?php
				// ini_set('display_errors', 1);
				// ini_set('display_startup_errors', 1);
				// error_reporting(E_ALL);

				//check is file format is any in "jpg", "jpeg", "gif","png"
				function isImage($fileType){
					$types = ["jpg", "jpeg", "gif", "png", "JPG", "JPEG", "GIF","PNG"];
					return in_array($fileType, $types);
				}

				//get file format by taking characters after dot
				function getFileType($file){
					$endOfName = 0;
					for($i = strlen($file)-1; $i > 0; $i--){
						if($file[$i] == "."){
							$endOfName = $i+1;
							break;
						}
					}
					return substr($file, $endOfName);
				}

				$dir = './files';
				// $fileList = scandir($dir);
				$fileList = array();
				//use filename as the key, and take value of the create date and time for sorting by date
				foreach (scandir($dir) as $file) {
					if ($file[0] === '.') continue;
					$fileList[$file] = filemtime($dir . '/' . $file);
				}

				arsort($fileList);
    			$fileList = array_keys($fileList);

				// $fileList = array_reverse($fileList);

				$i = 0;
				foreach($fileList as $filename){
					if($filename[0] !== '.' && !isImage(getFileType($filename))){
						$i++;
						echo "<label>$i. </label>";
						echo "<a href='$dir/$filename'>$filename</a>";
						echo "<label>(</label>";
						echo "<a href ='./deleteFile.php?fname=", $filename, "'>delete</a>";
						echo "<label>)</label>";
						echo "<br>";
					}
				}
			?>

			<br>
			<br>
			<h2 id="images-title">Images</h2>
			<div class="container">
				<div class="row">

			<?php
				$i = 0;
				foreach($fileList as $filename){
					if($filename[0] !== '.' && isImage(getFileType($filename))){
						echo '<div class="col-6 col-sm-3">';
						echo	 '<div class="card text-dark bg-light mb-3 images" style="width: 18rem;">';
						echo		'<div class="card-body image-body">';
						echo			"<label>", ++$i, '.', "</label>";
						echo			'<div class="image-title">';
						echo				"<a class='image-title text-truncate' href='$dir/$filename'>$filename</a>";
						echo			'</div>';
						echo 			'<button onclick="', "deleteFile('", $filename, "')", '" type="button" class="btn-close float-end" aria-label="Close"></button>';
						echo		"</div>";
						echo		'<img src="', $dir, '/', $filename, '" class="image-previews" alt="', $filename, '">';
						echo 	'</div>';
						echo '</div>';
					}
				}
			?>
				</div>
			</div>
			<br>
			<br>
			<h2 id="links-title">Links</h2>

			<form action = "./addLink.php" method = "post">
				<div class="input-group mb-3">
					<input type="text" class="form-control" placeholder="Enter URL" aria-label="Enter URL" aria-describedby="button-addon2" name="nlink">
					<input class="btn btn btn-outline-primary" type="submit" value = "Add link" id="button-addon2"></input>
				</div>
			</form>

			<!-- <form action = "/addLink.php" method = "post">
				<input type="text" name = "nlink">
				<input type="submit" value = "Add link">
			</form> -->

			<?php
				$doc = new DOMDocument();
				$doc->load('./xml/links.xml');
				$linkList = $doc->getElementsByTagName('link');

				if(count($linkList) > 0){
					//Need to convert DOMNodeArray to array in order to reverse
					$links = array();
					foreach($linkList as $link){
						array_push($links, $link);
					}

					$links = array_reverse($links);
				
					$i = 0;
					foreach($links as $link){
						$id = $link->getElementsByTagName('id')->item(0)->nodeValue;
						$url = $link->getElementsByTagName('url')->item(0)->nodeValue;
						$owner = $link->getElementsByTagName('owner')->item(0)->nodeValue;
						$date = $link->getElementsByTagName('date')->item(0)->nodeValue;
						$star = $link->getElementsByTagName('star')->item(0)->nodeValue;
						$hide = $link->getElementsByTagName('hide')->item(0)->nodeValue;

						if($hide == 'FALSE'){
							if($star == 'TRUE')
								echo "*";
							else
								echo " ";
							echo "<label> " . ++$i . '. </label>';
							echo "<a  href = '" . $url . "'target='_blank'>";
							echo $url;
							echo "</a>";
							echo "<label>(</label>";
							// echo "<a href ='mark.php?t=links&id=$id&a=star'>star</a>";
							// echo " | ";
							echo "<a href ='./mark.php?t=links&id=$id&a=delete'>delete</a>";
							echo "<label>)</label>";
							echo "<br>";
						}
					}
				}
			?>
			<br>
			<br>
			<h2 id="notes-title">Notes</h2>
			<div class="input-group mb-3" style="height:200px;">
				<textarea id="nnote" type="text" class="form-control" placeholder="Enter Note" aria-label="Enter Note" aria-describedby="button-addon2" style="resize:none;"></textarea>
				<button class="btn btn btn-outline-primary" id="button-addon2" onclick="saveNote()">Add Note</button>
			</div>
			<!-- <textarea id="nnote"></textarea>
			<button onclick="saveNote()">Save</button>
			<br> -->

			<div class="container">
				<div class="row">
					<?php
						$doc = new DOMDocument();
						$doc->load('./xml/notes.xml');
						$noteList = $doc->getElementsByTagName('note');
						if(count($noteList) > 0){
							$notes = array();
							foreach($noteList as $note){
								array_push($notes, $note);
							}

							$notes = array_reverse($notes);

							$i = 0;
							foreach($notes as $note){
								$id = $note->getElementsByTagName('id')->item(0)->nodeValue;
								$content = $note->getElementsByTagName('content')->item(0)->nodeValue;
								$owner = $note->getElementsByTagName('owner')->item(0)->nodeValue;
								$date = $note->getElementsByTagName('date')->item(0)->nodeValue;
								$star = $note->getElementsByTagName('star')->item(0)->nodeValue;
								$hide = $note->getElementsByTagName('hide')->item(0)->nodeValue;

								if($hide == 'FALSE'){
									//Change the {shorthand} back to special characters
									$text = str_replace("{dq}", '"', $content);
									$text = str_replace("{sq}", "'", $text);
									$text = str_replace("{bs}", "\\", $text);
									$text = str_replace("{sc}", ";", $text);
									$text = str_replace("<", "&lt;", $text);
									$text = str_replace(">", "&rt;", $text);

									$textByLine = explode("{nl}", $text);

									echo '<div class ="col-6 col-sm-3">';
									echo 	'<div class="card text-dark bg-light mb-3 notes">';
									echo 		'<div class="card-header">';
									echo 			'<label>', ++$i, '. ', '</label>';
									echo 			"<a href ='javascript:selectAll(\"note-content-", $i, "\")'>Select</a>";
									echo 			'<button onclick="deleteNote(', $id, ')" type="button" class="btn-close float-end" aria-label="Close"></button>';
									echo 		'</div>';
									echo 		'<div class="card-body note-body">';
									echo 			'<p id="note-content-', $i, '" class="card-text">';
														foreach ($textByLine as $line){
															echo $line . "<br>";
														}
									echo 			'</p>';
									echo 		'</div>';
									echo 	'</div>';
									echo '</div>';
									// echo "</textarea>";
									// echo " (";
									// echo "<a href ='mark.php?t=notes&id=$id&a=star'>star</a>";
									// echo " | ";
									// echo "<a href ='mark.php?t=notes&id=$id&a=delete'>delete</a>";
									// echo ") ";
									// echo "<br>";
								}
							}
						}
					?>
				</div>
			</div>
		</div>
    </body>
</html>
