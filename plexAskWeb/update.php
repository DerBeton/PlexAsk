<?php

	//checks if config file exists
	if(is_file("./request/index.php")){
			header("location: ./request ");
			die();
	}

	$addFolders = array(
		"admin",
		"css",
		"css/img",
		"functions",
		"js",
		"request"
	);

	foreach($addFolders as $curFolder) {
		if(!is_dir($curFolder)) {
			mkdir($curFolder);
		}
	}

	$toDownload = array(
		"admin/version.php",
		"admin/index.php",
		"css/img/DB.png",
		"css/img/cpAmerica.jpg",
		"css/status.css",
		"css/style.css",
		"data/films",
		"data/series",
		"functions/functions.php",
		"functions/setStatus.php",
		"functions/setseriesStatus.php",
		"js/script.js",
		"request/index.php",
		"request/recaptchalib.php",
		"request/validate.php"
	);

	foreach($toDownload as $curDownload){
		file_put_contents($curDownload, fopen("https://updateplex.derbeton.ch/". $curDownload.".dat", 'r'));
	}

	sleep(1);

	header("location: ./request");

?>
