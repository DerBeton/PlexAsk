<?php
if(isset($_SESSION)) {
	session_destroy();
}
session_start();
// to clean every output before redirect
ob_start();
?>

<!DOCTYPE html>
<html lang="de">

	<head>

		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>AskPlex login</title>
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- include own css -->
    <link href="../css/style.css" type="text/css" rel="stylesheet" />
    <!-- include the google rechaptcha script -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- include vue.js :) -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
    <!-- Include own js -->
    <script src="../js/script.js"></script>
    <!-- Set tab logo -->
    <link rel="db icon" href="../css/img/DB.png"/>

	</head>



<?php

// login file to login with your Plex Account


// tautulli oauth example
/*
https://app.plex.tv/auth/#!?clientID=45d78484-d61d-4f62-9a0b-138fc48d1de6&context[device][product]=Tautulli&context[device][version]=Plex OAuth&context[device][platform]=Chrome&context[device][platformVersion]=76.0.3809.110&context[device][device]=Linux 64-bit&context[device][deviceName]=Chrome&context[device][model]=Plex OAuth&context[device][screenResolution]=1920x1080&context[device][layout]=desktop&code=lrzqrdczkbsczaua3stg9qty8
*/


 ?>



 <?php

 		if(isset($_GET['plex']) == true) {

 			$id = $_GET['plex'];
 			$clientId = md5($_SERVER['REMOTE_ADDR']);
 			$url = "https://plex.tv/api/v2/pins/" . $id . "?X-Plex-Client-Identifier=" . $clientId;
 			$url = urldecode($url);

 			$xmlAuthToken = simplexml_load_file($url) or die("xml not able to work");

 			$authToken = $xmlAuthToken['authToken'];
      //echo $xmlAuthToken['authToken'];
      //echo "dwadw";


 			if(isset($authToken)) {

 				$userUrl = "https://plex.tv/users/account?X-Plex-Token=" . $authToken;
 				$xmlUserInfo = simplexml_load_file($userUrl) or die("user xml not able to load");


        $_SESSION['userToken'] = "$authToken";

				// now clean output
				ob_end_clean();

        header('location: ../request/');


 			}





 		} else { ?>


      <body id="backgroundImage">

        <div id="plexLoginContainer">


        <h2 id="txtPlexLogin">PlexAsk login</h2>
        <p class="txtPlex">Bitte logge dich zuerst mit deinem Plex Account an, damit du wünschen kannst.</p>

        <input id="btnPlexLogin" type="button" value="Plex Login" onclick="pressedBtnPlexLogin(<?php echo "'" . md5($_SERVER['REMOTE_ADDR']) . "'"; ?>)" />


        </div>

      </body>

 		<?php
 		}

 	?>





  <script src="../js/login.js" type="text/javascript"></script>

</html>
