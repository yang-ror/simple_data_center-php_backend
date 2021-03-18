<!-- =============================================================================
 index.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-09
 last modified: 2021-03-17
 change log:
	2021-03-15:
		1. Optimized for mobile view
	2021-03-16:
		1. Store data in MongoDB instead of xml file
	2021-03-17:
		1. Now sends new note as post request since cookie and get have a size limit of 4096 bytes
		2. Fix a bug that links won't be truncated when there's no files on server
============================================================================= -->

<!doctype html>
<html lang="en">
    <head>
		<title>Simple Data Center</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0", charset=utf-8>
		<link rel="stylesheet" type="text/css" href="bootstrap-5.0.0-beta1-dist/css/bootstrap.min.css">

		<style>
			.file-name-holder{
				vertical-align:top;
				display: inline-block;
			}

			.file-name{
				max-width: 100%;
				display: inline-block;
			}

			.image-previews{
				max-width: 100%;
			}

			.images{
				max-height: 18rem;
				overflow: hidden;
			}

			.image-card-title{
				padding: 5px;
				padding-left: 15px;
				padding-top: 10px;
			}

			.image-title-holder{
				vertical-align:top;
				display: inline-block;
			}

			.image-title{
				max-width: 100%;
				display: inline-block;
			}

			.link-holder{
				vertical-align:top;
				display: inline-block;
			}

			.link-a{
				max-width: 100%;
				display: inline-block;
			}

			.notes{
				max-width: 18rem;
				height: 18rem;
			}

			.note-body{
				overflow-y: auto;
			}

			.note-text{
				height: 100%;
			}
		</style>

		<script>
			//delete file by the filename
			function deleteFile(filename){
				window.location.replace("./deleteFile.php?fname=" + filename);
			}

			//delete link by the id
			function deleteLink(id){
				window.location.replace("./deleteLink.php?id=" + id);
			}

			//select the text in a card and copy to clipboard
			function selectAllAndCopy(element){
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

				if (document.selection) {
					var range = document.body.createTextRange();
					range.moveToElementText(document.getElementById(element));
					range.select().createTextRange();
					document.execCommand("copy");
				} else if (window.getSelection) {
					var range = document.createRange();
					range.selectNode(document.getElementById(element));
					window.getSelection().addRange(range);
					document.execCommand("copy");
				}
			}

			//delete note by id
			function deleteNote(id){
				window.location.replace("./deleteNote.php?id=" + id);
			}

			function resizeElement(){
				var imagesCardTitles = document.getElementsByClassName("image-card-title");
				if(imagesCardTitles.length > 0){
					var widthOfTitleBar = imagesCardTitles[0].clientWidth;
					var widthOfTitle = widthOfTitleBar - 60;
					var widthOfTitleStr = widthOfTitle.toString() + "px";
					var imagesTitleHolders = document.getElementsByClassName("image-title-holder");
					for(var i = 0; i < imagesTitleHolders.length; i++){
						imagesTitleHolders[i].style.width = widthOfTitleStr;
					}
				}

				var files = document.getElementsByClassName("files");
				if(files.length > 0){
					var widthOfFileTitleHolder = files[0].clientWidth;
					var widthOfFileTitle = widthOfFileTitleHolder - 80;
					var widthOfFileTitleStr = widthOfFileTitle.toString() + "px";
					var fileNameHolder = document.getElementsByClassName("file-name-holder");
					for(var i = 0; i < fileNameHolder.length; i++){
						fileNameHolder[i].style.width = widthOfFileTitleStr;
					}
				}

				var links = document.getElementsByClassName("links");
				if(links.length > 0){
					var widthOfLinkTitleHolder = links[0].clientWidth;
					var widthOfLinkTitle = widthOfLinkTitleHolder - 80;
					var widthOfLinkTitleStr = widthOfLinkTitle.toString() + "px";
					var linkHolders = document.getElementsByClassName("link-holder");
					for(var i = 0; i < linkHolders.length; i++){
						linkHolders[i].style.width = widthOfLinkTitleStr;
					}
				}
			}

			document.addEventListener('DOMContentLoaded', function(){
				// document.getElementById("radiobutton")
				resizeElement();
			});
		</script>
	</head>

	<body onresize="resizeElement()">
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

			<ul class="list-group">
			<?php
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 1);
				error_reporting(E_ALL);

				require 'vendor/autoload.php';

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
						echo '<li class="list-group-item files">';

							$i++;
							echo "	<label>$i.</label>";

							echo '	<div class="file-name-holder">';
							echo "		<a class='file-name text-truncate' href='$dir/$filename'>$filename</a>";
							echo '	</div>';

							echo '	<button onclick="', "deleteFile('", $filename, "')", '" type="button" class="btn-close float-end" aria-label="Close"></button>';

						echo '</li>';
					}
				}
			?>
			</ul>

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
							echo	 '<div class="card text-dark bg-light mb-3 images">';
							echo		'<div class="card-body image-body image-card-title">';
							echo			"<label>", ++$i, '.', "</label>";
							echo			'<div class="image-title-holder">';
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

			<ul class="list-group">
			<?php
				$client = new MongoDB\Client("mongodb://localhost:27017");
				$collection = $client->datacenter->links;
				$options = ['sort' => ['id' => -1]];
				$links = $collection->find([ 'hide' => false ], $options);

				$i = 0;
				foreach($links as $link){
					$id = $link['id'];
					$title = $link['title'];
					$url = $link['url'];

					echo '<li class="list-group-item links">';

					echo "	<label> " . ++$i . '. </label>';

					echo '<div class="link-holder">';
					echo "		<a class='link-a text-truncate' href = '" . $url . "'target='_blank'>" . $title . "</a>";
					echo '</div>';

					echo '<button onclick="', "deleteLink('", $id, "')", '" type="button" class="btn-close float-end" aria-label="Close"></button>';

					echo "</li>";
				}
			?>
			</ul>

			<br>
			<br>
			<h2 id="notes-title">Notes</h2>
			<form action = "./addNote.php" method = "post">
				<div class="input-group mb-3" style="height:200px;">
					<textarea id="nnote" type="text" class="form-control" name="nnote" placeholder="Enter Note" aria-label="Enter Note" aria-describedby="button-addon2" style="resize:none;"></textarea>
					<input class="btn btn btn-outline-primary" type="submit" value = "Add Note" id="button-addon2"></input>
				</div>
			</form>

			<div class="container">
				<div class="row">
					<?php
						$client = new MongoDB\Client("mongodb://localhost:27017");
						$collection = $client->datacenter->notes;
						$options = ['sort' => ['id' => -1]];
						$notes = $collection->find([ 'hide' => false ], $options);

						$i = 0;
						foreach($notes as $note){
							$id = $note['id'];
							$content = $note['content'];

							// $content = str_replace("{sc}", ";", $content);
							// $content = str_replace("{tb}", "\t", $content);

							// Change '<' nad '>' to "&lt;" and "&gt;" to avoid to be displaied as html elements;
							$content = str_replace("<", "&lt;", $content);
							$content = str_replace(">", "&gt;", $content);
							

							$textByLine = explode("{nl}", $content);

							echo '<div class ="col-6 col-sm-3">';
							echo 	'<div class="card text-dark bg-light mb-3 notes">';
							echo 		'<div class="card-header">';
							echo 			'<label>', ++$i, '. ', '</label>';
							echo 			"<a href ='javascript:selectAllAndCopy(\"note-content-", $i, "\")'>Copy</a>";
							echo 			'<button onclick="deleteNote(', $id, ')" type="button" class="btn-close float-end" aria-label="Close"></button>';
							echo 		'</div>';
							echo 		'<div class="card-body note-body">';
							echo 			'<pre id="note-content-', $i, '" class="card-text note-text">';
												foreach ($textByLine as $line){
													echo $line . "<br>";
												}
							echo 			'</pre>';
							echo 		'</div>';
							echo 	'</div>';
							echo '</div>';
						}
					?>
				</div>
			</div>
		</div>
		<script src = "bootstrap-5.0.0-beta1-dist/js/bootstrap.js"></script>
	</body>
</html>