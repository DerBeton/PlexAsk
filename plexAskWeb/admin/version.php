<?php

  $config = json_decode(file_get_contents("../data/config.json"), true);

  if (isset($_POST["token"]) && $_POST['token'] == $config['admin']['token']) {

    $response = array();

    $version = $config['version']['number'];

    $versions = json_decode(file_get_contents("https://updateplex.derbeton.ch/versions.json"), true);

    foreach ($versions['version'] as $accVersion) {
        $curVersion = $accVersion['number'];
    }

    if(version_compare($curVersion, $version, ">")) {


      file_put_contents("../updatever.php", fopen("https://updateplex.derbeton.ch/updatever.php.dat", 'r'));


      $response['status'] = 'update';
      $response['version'] = $curVersion;


    } else {


      $response['status'] = 'upToDate';
      $response['version'] = $version;




    }

    // echo the response array
    echo json_encode($response);


  } else {

      	header("location: ../request/");

  }


 ?>
