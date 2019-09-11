<?php

	// set default timezone
	date_default_timezone_set('Europe/Zurich');

	// get token from config
	$config = json_decode(file_get_contents("data/config.json"), true);

	// compare token else go back to main site
	if (isset($_GET["t"]) && $_GET['t'] == $config['admin']['token']) {
		
		// create backup zip in backup folder
		createZip("./"); // pass path
		
		// context for github api otherwise request is rejected
		$opts = [
						'http' => [
										'method' => 'GET',
										'header' => [
														'User-Agent: PHP'
										]
						]
		];
		$context = stream_context_create($opts);
		$remoteRepo = json_decode(file_get_contents("https://api.github.com/repos/DerBeton/PlexAsk/git/trees/update-process", false, $context));

		// get plexAskWeb folder url on github api
		foreach($remoteRepo->tree as $key=>$value) {

			if($value->path == "plexAskWeb") {
				$mainUrl = $value->url;
				getFiles($mainUrl, $context, "");
				break;
			}

		}
		
		// when update is finished, go to main site with update = true
		$config['version']['number'] = $_GET['ver'];
		file_put_contents("data/config.json",json_encode($config));
		
		header("location: request?update=true");


	} else {

				// if token isn't right
				header("location: request?update=false");

	}

	
	##################################################################################

									// Erstelle ein zip Archive im backup ordner //

	##################################################################################


	function createZip($path) { 

		// if backup folder doesn't exist create it
		if(!is_dir("backup")) {
			mkdir("backup");
		}
		
		// path and name of new zip file
		$zipcreated = "./backup/backup-" . date('d-m-Y:H:i:s') . ".zip"; 

		// Create new zip class 
		$zip = new ZipArchive; 

		if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) { 

				// get all subfolders in path
				$subdirs = array_filter(glob('*'), 'is_dir');
			
			
				// go through array and save folders in $dir
				foreach($subdirs as $dir) {
					
					// dont backup the backup folder
					if ($dir == "backup") {
						continue;
					}
					
					// get the id of folder for readdir
					$curdir = opendir($dir); 
					
					// add every file in zip with subfolder and dont backup config.json (security reason)
					while($file = readdir($curdir)) { 
							if(is_file($path.$dir."/".$file) and $file != "config.json") { 
									$zip -> addFile($path.$dir."/".$file, $dir."/".$file); 
							}
					}
					
				}
			
				$zip ->close(); 
		} 
	
	} // end createZip

	##################################################################################

									// get files from Github PlexAsk master repository //

	##################################################################################


	function getFiles($mainUrl, $context, $path) {
		
		$remoteFiles = json_decode(file_get_contents($mainUrl, false, $context));
		
		foreach($remoteFiles->tree as $key=>$value) {
			
			if($value->type == "blob") {
				
				$file = $value->path;
				$filePath = $path.$value->path;
				if(file_exists($filePath)) {

					$localFile = file_get_contents($filePath);					
					
				} else {
					
					if(!is_dir($path)) {
						mkdir($path);
					}					
					
					$localFile = NULL;
				
				}
				

				// pfff need to compare hash with blob, size and \0 and than the filecontent because github uses the git hash-object function to hash object
				if($value->sha != sha1("blob " . $value->size . "\0" . $localFile) and $file != "updatever.php" and $file != "films" and $file != "series" and $file != "config.json") {
					
					downloadFile($file, $path);
					
				} else {
				
					echo "File " . $file . " ist gleich wie auf github";
					echo "</br>";
					
				}
			
			} elseif($value->type == "tree") {
				
				$subUrl = $value->url;
				$subPath = $path . $value->path . "/";
				
				getFiles($subUrl, $context, $subPath);
				
				
				}
		
		}
	
	} // end getFiles

	
	##################################################################################

											// download Files which were udpated //

	##################################################################################

	function downloadFile(String $file,String $path) {
		
		file_put_contents($path.$file, fopen("https://raw.githubusercontent.com/DerBeton/PlexAsk/update-process/plexAskWeb/".$path.$file, "r"));
		
		echo "File " . $file . " wurde auf den neusten Stand gebracht";
		echo "</br>";
	
	
	}


?>
