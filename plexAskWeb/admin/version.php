<?php

  $config = json_decode(file_get_contents("../data/config.json"), true);
  $localVersion = $config['version']['number'];

  if (isset($_POST["token"]) && $_POST['token'] == $config['admin']['token']) {

    $response = array();

    $remoteVersionJson = json_decode(file_get_contents("https://raw.githubusercontent.com/DerBeton/PlexAsk/update-process/plexAskWeb/version.json", false));

    $remoteVersion = $remoteVersionJson->version->number;

    if(version_compare($remoteVersion, $localVersion, ">")) {

      file_put_contents("../updatever.php", fopen("https://raw.githubusercontent.com/DerBeton/PlexAsk/update-process/plexAskWeb/updatever.php", 'r'));
      $response['status'] = 'update';
      $response['version'] = $remoteVersion;


    } else {

      $response['status'] = 'upToDate';
      $response['version'] = $localVersion;

    }

    // echo the response array
    echo json_encode($response);


  } else {

      	header("location: ../request/");

  }


 ?>
