  <!-- =============================================================================
 index.php
 project: Simple Data Center
 author: Zifan Yang
 date created: 2020-07-09
 change log:
	2021-03-15:
		1. Optimized for mobile view
	2021-03-16:
		1. Store data in MongoDB instead of xml file
	2021-03-17:
		1. Now sends new note as post request since cookie and get have a size limit of 4096 bytes
		2. Fixed a bug that links won't be truncated when there's no files on server
	2021-03-18:
  		1. Show a progress bar when uploading, and added jQuery and jQuery-Form library to acheive this
		2. Now store data in json files instead of MongoDB, since MongoDB is not suppport on 32-bit platforms
		3. Fixed a bug where deleteFile.php doesn't work when file name contains '&' or '+'
		4. css and javascript are now independent files in their sub-directories
		5. move php files for backend operation to the php sub-directory
		6. combine deleteLink.php and deleteNote.php to one php file, delete.php
============================================================================= -->
<!doctype html>
<html lang="en">
    <head>
		<title>Simple Data Center</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0", charset=utf-8>
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./css/styles.css">
		<script src="./scripts/jquery-3.6.0.min.js"></script>
		<script src="./scripts/jquery.form.js"></script>
		<script src="./scripts/javascript.js"></script>
	</head>

	<body onresize="resizeElement()">
		<div class="container">
			<h1>Simple Data Center</h1>
			
			<div class="btn-group" role="group">
				<button type="button" class="btn btn-primary" onclick="location.href='#files-title';">Files</button>
				<button type="button" class="btn btn-primary" onclick="location.href='#images-title';">Images</button>
				<button type="button" class="btn btn-primary" onclick="location.href='#links-title';">Links</button>
				<button type="button" class="btn btn-primary" onclick="location.href='#notes-title';">Notes</button>
			</div>

			<br>
			<br>

			<h2 id="files-title">Files</h2>

			<form id="uploadForm" action="./php/upload.php" method ="post" enctype="multipart/form-data">
				<div class="input-group mb-3">
					<input type="file" class="form-control" id="nfile" name="nfile"></input>
					<input class="input-group-text btn btn-outline-primary" for="nfile" type="submit" value = "Upload"></input>
				</div>
				<div class="progress" id="progress_div">
					<div id="upload-progress-bar" class="progress-bar"role="progressbar" style="width: 0%;"></div>
				</div>
				<br>
			</form>

			<ul class="list-group">

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

				$i = 0;
				foreach($fileList as $filename){
					if($filename[0] !== '.' && !isImage(getFileType($filename))){
						$i++;
						echo '<li class="list-group-item files">';
						echo 	"<label>$i.</label>";
						echo 	'<div class="file-name-holder a-holder">';
						echo 		"<a class='file-name link-a text-truncate' href='$dir/$filename'>$filename</a>";
						echo 	'</div>';
						echo 	'<button onclick="', "deleteFile('", $filename, "')", '" type="button" class="btn-close float-end" aria-label="Close"></button>';
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
								echo			'<div class="image-title-holder a-holder">';
								echo				"<a class='image-title link-a text-truncate' href='$dir/$filename'>$filename</a>";
								echo			'</div>';
								echo			'<button onclick="', "deleteFile('", $filename, "')", '" type="button" class="btn-close float-end" aria-label="Close"></button>';
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

			<form action = "../php/addLink.php" method = "post">
				<div class="input-group mb-3">
					<input type="text" class="form-control" placeholder="Enter URL" aria-label="Enter URL" aria-describedby="button-addon2" name="nlink"></input>
					<input class="btn btn btn-outline-primary" type="submit" value = "Add link" id="button-addon2"></input>
				</div>
			</form>

			<ul class="list-group">
			<?php
				$jsonPath = './json/links.json';
				$jsonFile = file_get_contents($jsonPath);
				$links = json_decode($jsonFile, true);

				$i = 0;
				$links = array_reverse($links);

				foreach($links as $link){
					if($link['hide'] == FALSE){
						$id = $link['id'];
						$title = $link['title'];
						$url = $link['url'];

						echo '<li class="list-group-item links">';
						echo	"<label> " . ++$i . '. </label>';
						echo	'<div class="link-holder a-holder">';
						echo 		"<a class='link-a text-truncate' href = '" . $url . "'target='_blank'>" . $title . "</a>";
						echo	'</div>';
						echo	'<button onclick="', "deleteLink('", $id, "')", '" type="button" class="btn-close float-end" aria-label="Close"></button>';
						echo "</li>";
					}
				}
			?>
			</ul>

			<br>
			<br>

			<h2 id="notes-title">Notes</h2>

			<form action = "./php/addNote.php" method = "post">
				<div class="input-group mb-3" style="height:200px;">
					<textarea id="nnote" type="text" class="form-control" name="nnote" placeholder="Enter Note" aria-label="Enter Note" aria-describedby="button-addon2" style="resize:none;"></textarea>
					<input class="btn btn btn-outline-primary" type="submit" value = "Add Note" id="button-addon2"></input>
				</div>
			</form>

			<div class="container">
				<div class="row">
					<?php
						$jsonPath = './json/notes.json';
						$jsonFile = file_get_contents($jsonPath);
						$notes = json_decode($jsonFile, true);

						$i = 0;
						$notes = array_reverse($notes);

						foreach($notes as $note){
							if($link['hide'] == FALSE){
								$id = $note['id'];
								$content = $note['content'];
								$content = str_replace("<", "&lt;", $content);
								$content = str_replace(">", "&gt;", $content);

								echo '<div class ="col-6 col-sm-3">';
								echo 	'<div class="card text-dark bg-light mb-3 notes">';
								echo 		'<div class="card-header">';
								echo 			'<label>', ++$i, '. ', '</label>';
								echo 			"<a href ='javascript:selectAllAndCopy(\"note-content-", $i, "\")'>Copy</a>";
								echo 			'<button onclick="deleteNote(', $id, ')" type="button" class="btn-close float-end" aria-label="Close"></button>';
								echo 		'</div>';
								echo 		'<div class="card-body note-body">';
								echo 			'<pre id="note-content-', $i, '" class="card-text note-text">';
								echo				$content;					
								echo 			'</pre>';
								echo 		'</div>';
								echo 	'</div>';
								echo '</div>';
							}
						}
					?>
				</div>
			</div>
		</div>
		<script src = "./scripts/bootstrap.min.js"></script>
	</body>
</html>