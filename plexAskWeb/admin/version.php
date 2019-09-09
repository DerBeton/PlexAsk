<?php

  $config = json_decode(file_get_contents("../data/config.json"), true);
  $localVersion = json_decode(file_get_contents("../version.json"), true);

  if (isset($_GET["token"]) && $_GET['token'] == $config['admin']['token']) {

    $response = array();

    $version = $localVersion['version']['number'];

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

    $remoteVersion = json_decode(file_get_contents("https://api.github.com/repos/DerBeton/PlexAsk/git/trees/update-process", true, $context));

    //https://api.github.com/repos/DerBeton/PlexAsk/git/trees/update-process

    foreach ($remoteVersion as $key) {

      print_r($key);

      // code...
      echo $key['path'];

    }


    echo "</br>";

    //print_r($config);
    //echo $remoteVersion[''];

    $versions = json_decode(file_get_contents("https://updateplex.derbeton.ch/versions.json"), true);

    foreach ($versions['version'] as $accVersion) {
        $curVersion = $accVersion['number'];
    }

    if(version_compare($curVersion, $version, ">")) {


      //file_put_contents("../updatever.php", fopen("https://updateplex.derbeton.ch/updatever.php.dat", 'r'));


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
