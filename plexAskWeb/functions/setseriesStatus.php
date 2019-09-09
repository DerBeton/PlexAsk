<?php

	// check for token
	$token = $_POST['token'];
	$sitetoken = json_decode(file_get_contents("../data/config.json"), true);

	if($token == $sitetoken['admin']['token']) {
	} else {
		die();
	}

?>

<?php

	// php to set change the film status in admin site or with cronjob

if(isset($_POST['id'])){


	$series = json_decode(file_get_contents("../data/series"), true);
  $series[$_POST['id']]['status'] = $_POST['status'];
  file_put_contents("../data/series",json_encode($series));

	if(!empty($series[$_POST['id']]['email'])){

			include "functions.php";

			$to = $series[$_POST['id']]['email'];
			$id = $_POST['id'];
			$titel = $series[$_POST['id']]['serientitel'];
			$status = $_POST['status'];


			sendMail($to, $id, $titel, $status);


    }

}


?>

	<!--JavaScript at end of body for optimized loading-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>

         //setStatus($id, $status);


				function setStatus(id, status){

							console.log([id, status]);
							$.ajax({
									method: "post",
									data: {
											id: id,
											status: status
									},
									success: function(result){
											location.reload();
									}
							});

					}

				</script>
