<?php
 //PlexAsk ajax php functions

// später noch nach Session überprüfen !!!
if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case "checkAdminMail":
			//checkAdminMail();
			break;
		case "writeToJson":
			//writeToJson($_POST['keyOne'],$_POST['keyTwo'],$_POST['value']);
			break;
		case "saveSettings":
			//error_log($_POST['config'] . "\n", 3, "./error.log");
			saveSettings($_POST['data']);
			break;
		case "getUsers":
			getUsers();
			break;
		case "deleteUser":
			deleteUser($_POST['index']);
			break;
		default:
			break;
	}
}

function saveSettings($jsonObject) {

	require '../library/TreeWalker.php';

	$treewalker = new TreeWalker(array(
    "debug"=>false,                      //true => return the execution time, false => not
    "returntype"=>"jsonstring")         //Returntype = ["obj","jsonstring","array"]
  );


	$configOld = json_decode(file_get_contents("../data/config.json"), true);
	$configNew = json_decode($jsonObject, true);

	//$test = $compareObjects = $treewalker->getdiff($configNew, $configOld, false); // false -> with slashs



	$newSettings = $treewalker->structMerge($configNew, $configOld, true); //true -> No slashs

	//$response = $treeWalker->getdiff($configOld, $configNew);


	//error_log($dataJson . "\n", 3, "./error.log");

	//$version = $dataJson['version']['number'];

	if(file_put_contents("../data/config.json",$newSettings)) {
		$response['saved'] = true;
	} else {
		$response['saved'] = false;
	}

	echo json_encode($response);

}

/* get users from config.json for settings page */
function getUsers() {

	//$userMail = $_SESSION['userEmail'];

  $config = json_decode(file_get_contents("../data/config.json"), true);
  $users = $config['users'];

	echo json_encode($users);

  // foreach ($users as $user) {
  //   echo $user['email'] . " " . $user['adminPanel'] . $user['editSettings'];
  // }

}

function deleteUser($index) {
	$config = json_decode(file_get_contents("../data/config.json"), true);
	unset($config['users'][$index]);

	if(file_put_contents("../data/config.json", json_encode($config))) {
		$response['deleted'] = true;
	} else {
		$response['deleted'] = false;
	}

	echo json_encode($response);

}


?>
