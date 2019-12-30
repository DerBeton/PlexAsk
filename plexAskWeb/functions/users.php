<?php
session_start();

/* get users from config.json for settings page */
getUsers();

function getUsers() {
$userMail = $_SESSION['userEmail'];

  $config = json_decode(file_get_contents("../data/config.json"), true);
  $users = $config['users'];

  foreach ($users as $user) {
    echo $user['email'] . " " . $user['adminPanel'] . $user['editSettings'];
  }

}
 ?>
