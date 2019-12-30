<?php
// check if admin is authentificated
$GLOBALS['accessForm'] = false;
$GLOBALS['isAdmin'] = false;
$GLOBALS['adminPanel'] = false;
$GLOBALS['editSettings'] = false;
$userMail = $_SESSION['userEmail'];

// load config
$config = json_decode(file_get_contents("../data/config.json"), true);

if (isset($config['users'])) {
  $users = $config['users'];
} else {
  //$addUsers["users"] = array();
  $config['users'] = [];
  file_put_contents('../data/config.json',json_encode($config));
  unset($config);
}

// print version to console
$version = $config['version']['number'];
echo "<script>console.log( 'PlexAsk Version: " . $version . "' )</script>";


  if(isset($_SESSION['userEmail']) && strtolower($_SESSION['userEmail']) == strtolower($config['admin']['email'])) {
    $GLOBALS['accessForm'] = true;
    $GLOBALS['isAdmin'] = true;
    $GLOBALS['adminPanel'] = true;
    $GLOBALS['editSettings'] = true;
  } else {

    if(isset($_GET['t']) && $_GET['t'] == $config['admin']['token']) {
      $GLOBALS['accessForm'] = false;
      $GLOBALS['isAdmin'] = false;
      $GLOBALS['adminPanel'] = true;
      $GLOBALS['editSettings'] = false;
    }

  }

  foreach($users as $user) {
    if($user['email'] == $userMail){

      $GLOBALS['accessForm'] = true;

      if($user['adminPanel'] == true) {
        $GLOBALS['adminPanel'] = true;
      }
      if($user['editSettings'] == true) {
        $GLOBALS['editSettings'] = true;
      }
    }
  }

function isAdmin() {
  return $GLOBALS['isAdmin'];
}

function editSettings() {
  return $GLOBALS['editSettings'];
}

function adminPanel() {
  return $GLOBALS['adminPanel'];
}

function accessForm() {
  return $GLOBALS['accessForm'];
}

?>
