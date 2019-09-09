<?php

		//do update to version

		//if(isset($_GET['ver'])) {

	$config = json_decode(file_get_contents("data/config.json"), true);

	if (isset($_GET["t"]) && $_GET['t'] == $config['admin']['token']) {

		$version = $_GET['ver'];

		$folder = "https://updateplex.derbeton.ch/" . "ver" . $version;

		$toDownload = json_decode(file_get_contents($folder . "/version.json"), true);

		foreach ($toDownload['file'] as $curDownload) {
			//echo $curDownload['name'] . '<br>';
			file_put_contents($curDownload['name'], fopen($folder . "/" . $curDownload['name'].".dat", 'r'));
		}


		$config = json_decode(file_get_contents("data/config.json"), true);
		$config['version']['number'] = $version;
		file_put_contents("data/config.json",json_encode($config));

		header("location: request/");


	} else {

				header("location: request/");

	}

?>
