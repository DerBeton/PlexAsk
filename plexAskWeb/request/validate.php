<?php
session_start();
?>

<?php

    $secretkey = json_decode(file_get_contents("../data/config.json"), true);
    $secret=$secretkey['recaptcha']['secretkey'];
    $response=$_POST["captcha"];

    $verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
    $captcha_success=json_decode($verify);
    if ($captcha_success->success==false) {
      //This user was not verified by recaptcha.
      //echo "captcha verloren";
      echo "Bitte bestätige zuerst das Captcha!";
    }
    else if ($captcha_success->success==true) {
      //This user is verified by recaptcha
      //echo "captcha bestanden";

      if (isset($_SESSION['userToken'])) {

        $userToken = $_SESSION['userToken'];

        //get email with userToken
        $userUrl = "https://plex.tv/users/account?X-Plex-Token=" . $userToken;
        $xmlUserInfo = simplexml_load_file($userUrl) or die("user xml not able to load");

        $userEmail = $xmlUserInfo['email'];


      } else {

          die("Bitte logge dich erneut ein Session ist nicht mehr vorhanden!");

      }

        include "../functions/functions.php";

        // get type (film or serie)
        $type = $_POST['type'];


        if ($type == "film") {

          $mediatitel = $_POST['filmtitel'];
          $id = hash("md5", $mediatitel.$_POST['beschreibung']);

          if(isOnPlex($mediatitel, $type) == true) {

              die("Film ist schon auf dem Plex");

          }

          // call putinfilms in functions.php
          putinfilms("$userEmail");


          echo "Filmwunsch wurde abgeschickt";

          // call sendMail in functions.php
          sendMail($userEmail, $id, $mediatitel, "requested");


        } elseif ($type == "serie") {


          $mediatitel = $_POST['serientitel'];
          $staffel = $_POST['staffel'];
          $id = hash("md5", $mediatitel.$_POST['Sbeschreibung']);

          if(isOnPlex($mediatitel, $type) == true) {

              die("der Serie " . $mediatitel . " ist schon auf dem Plex");

          }


          // call putinseries in functions.php
          putinseries("$userEmail");

          echo "Serienwunsch wurde abgeschickt";

          // call sendMail in functions.php
          sendMail($userEmail, $id, $mediatitel, "requested");

        }

      }
?>
