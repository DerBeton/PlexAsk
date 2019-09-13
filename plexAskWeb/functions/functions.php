<?php


	// List PHP errors
/*
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
*/




	##################################################################################

												// Prüfen ob auf Film / Serie auf Plex //

	##################################################################################

	function isOnPlex(String $title, String $type) {

		$configurationArray = json_decode(file_get_contents("../data/config.json"), true);
		$token = $configurationArray['plex']['token'];
		$ip =  $configurationArray['plex']['ip'];
		$port = $configurationArray['plex']['port'];

		$seriesIsOnPlex = false;

		if($type == "film") {

			foreach (simplexml_load_file("http://".$ip.":".$port."/library/sections/1/all?title=" . $title . "&X-Plex-Token=".$token."&includeHttps=1")->Video as $key => $value) {
				if($value['title'][0] . "" == $title){
					return true;
				}
			}
			return false;

		}  elseif ($type == "serie") {

			$staffel = $_POST['staffel'];

			if (isset($staffel)) {

					foreach (simplexml_load_file("http://".$ip.":".$port."/library/sections/2/all?title=" . $title . "&X-Plex-Token=".$token."&includeHttps=1")->Directory as $key => $value) {

							if($value['title'][0] . "" == $title){

								$mediaId = $value['ratingKey'];

								$seriesIsOnPlex = true;

								break;

							}

					} //end foreach

				if ($seriesIsOnPlex == true) {

					$serienXml = simplexml_load_file("http://".$ip.":".$port."/library/metadata/" . $mediaId . "/children?X-Plex-Token=".$token."&includeHttps=1");

					foreach ($serienXml as $key => $value) {

						if ($value['index'] == $staffel) {
							echo "Staffel " . $staffel . " ";
							return true;
						}

					}

					return false;




				} else {

					return false;

				}


		} else {

				return false;

		}

	}

	}

	##################################################################################

												// Refresh Plex IP Function //

	##################################################################################

	// function to refresh plex server IP. calls automatically if plex isn't available
		function refreshIP(String $filmTitle) {

		// initialisiere curl
		$ch = curl_init();

		// set URL to plex refresh IP command
		curl_setopt($ch, CURLOPT_URL, "https://raymond.gipfelibueb.ch/proxy-raymond.php?checkip=true");
		curl_setopt($ch, CURLOPT_HEADER, 0);

		// grab URL and pass it to the browser
		curl_exec($ch);

		// close cURL resource, and free up system resources
		curl_close($ch);

		// call film check function again with the new IP
		isOnPlex($filmTitle);
		}


		##################################################################################

													// In film File schreiben Funktion //

		##################################################################################

		function putinfilms($email) {

      $id = hash("md5", $_POST['filmtitel'].$_POST['beschreibung']);

      $object = array(
              "imdbId" => $_POST['id'],
              "filmtitel" => $_POST['filmtitel'],
              "Fbeschreibung" => $_POST['beschreibung'],
              "email" => $email,
              "requestIp" => $_SERVER['REMOTE_ADDR'],
              "requestTime" => (new DateTime())->getTimestamp(),
              "downloadLink" => "",//$output,
              "status" => "requested"
          );

      $films = json_decode(file_get_contents("../data/films"), true);
      $films[$id] = $object;
      file_put_contents("../data/films", json_encode($films));

    }

		##################################################################################

													// In film Serie schreiben Funktion //

		##################################################################################

    function putinseries($email) {


      $id = hash("md5", $_POST['serientitel'].$_POST['Sbeschreibung']);

      $object = array(
              "imdbId" => $_POST['id'],
              "serientitel" => $_POST['serientitel'],
              "staffel" => $_POST['staffel'],
              "Sbeschreibung" => $_POST['Sbeschreibung'],
              "email" => $email,
              "requestIp" => $_SERVER['REMOTE_ADDR'],
              "requestTime" => (new DateTime())->getTimestamp(),
              "status" => "requested"
          );

      $series = json_decode(file_get_contents("../data/series"), true);
      $series[$id] = $object;
      file_put_contents("../data/series", json_encode($series));


    }

		##################################################################################

													// Mail versenden Function //

		##################################################################################


		// function to send mail
		function sendMail(String $to, String $id, String $titel, String $status) {

			//get adminMAil for sender and more
			$configurationArray = json_decode(file_get_contents("../data/config.json"), true);

			$emailTemplates = array(
				"requested" => array(
					"subject" => "PlexAsk | News von deinem Antrag #".$id." für ".$titel,
					"body" => "
					<html>
					<head><meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
					<title>" . $titel . "</title>
					</head>
					<body>
					<h1>" . $titel . ": Status</h1>
					<p> Der Antrag mit dem Titel " . $titel . ", wurde zu den gewünschten Anträgen hinzugefügt</p><br /><br />
					<small>Vielen Dank fuer das entgegengebrachte Vertrauen... &copy;PlexAsk</small>
					</body>
					</html>
					"
				),
				"downloading" => array(
					"subject" => ("PlexAsk | News von deinem Antrag #".$id." für ".$titel),
					"body" => "
					<html>
					<head><meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
					<title>Wir arbeiten gerade daran, " . $titel . " für dich zu beschaffen!</title>
					</head>
					<body>
					<h1>Wir arbeiten gerade daran, " . $titel . " für dich zu beschaffen!</h1>
					<p>Wir haben deinen Wunsch erhört und uns sofort an die Arbeit gemacht deinen Antrag mit dem Namen <b>". $titel ."</b> (Referenz #".$id.") zu bearbeiten.</p><br />
					<p>Du erhälst weitere Informationen sobald der Film auf dem Plex auffindbar ist.</p><br /><br />
					<small>Vielen Dank fuer das entgegengebrachte Vertrauen... &copy;PlexAsk</small>
					</body>
					</html>
					"
				),
				"finished" => array(
					"subject" => ("PlexAsk | News von deinem Antrag #".$id." für ".$titel),
					"body" => "
					<html>
					<head><meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
					<title>Wir haben gute Neuigkeiten!</title>
					</head>
					<body>
					<h1>Wir haben gute Neuigkeiten!</h1>
					<p>Dein Antrag mit dem Namen <b>". $titel ."</b> (Referenz #".$id.") wurde erfolgreich bearbeitet. Du solltest in Kürze auf die gewünschte Ressource zugreifen können.</p><br /><br />
					<small>Vielen Dank fuer das entgegengebrachte Vertrauen... &copy;PlexAsk</small>
					</body>
					</html>
					"),
					"deleted" => array(
						"subject" => ("PlexAsk | News von deinem Antrag #".$id." für ".$titel),
						"body" =>
						"<html>
						<head><meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
						<title>Leider mussten wir Ihren Antrag abweisen</title>
						</head>
						<body>
						<h1>Leider mussten wir Ihren Antrag abweisen</h1>
						<p>Dein Antrag mit dem Namen <b>". $titel ."</b> (Referenz #".$id.") wurde abgelehnt. Sollte es sich um ein Missverständnis handeln, melde dich bitte bei uns: ".$configurationArray['email']['address']."</p><br /><br />
						<small>Vielen Dank fuer das entgegengebrachte Vertrauen... &copy;PlexAsk</small>
						</body>
						</html>"
						)
				);


			$subject = $emailTemplates[$status]["subject"];
			$message = $emailTemplates[$status]["body"];


			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


			$sender = isset($configurationArray['email']) && isset($configurationArray['email']['address']) ? $configurationArray['email']['address'] : "info@derbeton.ch";

			$headers .= 'From: <'.$sender.'>' . "\r\n";

			mail($to,$subject,$message,$headers);

		}

?>
