<?php
session_start();

  $isAdmin = false;

    $config = json_decode(file_get_contents("../data/config.json"), true);
    $version = $config['version']['number'];
    echo "<script>console.log( 'PlexAsk Version: " . $version . "' )</script>";

    if(isset($_SESSION['userEmail']) && strtolower($_SESSION['userEmail']) == strtolower($config['admin']['email'])) {
      $isAdmin = true;
    } else {

      if(isset($_GET['t']) && $_GET['t'] == $config['admin']['token']) {
        $isAdmin = true;
      }

    }

    if($isAdmin == false) {
      header('Location: ../request/');
      die();
    }

?>

<!-- html -->

<!DOCTYPE html>
<html lang="de">
    <head>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link href="../css/status.css" type="text/css" rel="stylesheet" />
        <!-- Set tab logo -->
				<link rel="db icon" href="../css/img/DB.png"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="utf-8"/>
        <title>PlexAsk</title>
    </head>
    <body>




<?php

   $films = json_decode(file_get_contents("../data/films"), true);

   $series = json_decode(file_get_contents("../data/series"), true);

?>

      <!-- Formular Button -->
      <div id="d-formular">
          <input class="action-button" id="formular-button" type="button" value="Formular" />
      </div>

      <!-- Update Button -->
      <div id="d-update">
          <input class="action-button" id="update-button" type="button" value="Update!" />
      </div>

      <!-- Ceate Tabs -->
      <nav id="myTab" class="nav nav-pills nav-fill">
        <a class="nav-item nav-link" data-toggle="pill" href="#nfilme">Filme</a>
        <a class="nav-item nav-link" data-toggle="pill" href="#nserien">Serien</a>
      </nav>

      <!-- Tab Panels -->
      <div class="tab-content">

          <!-- Filme Tab -->
          <div id="nfilme" href="#nfilme" class="tab-pane fade in">
            <ul id="pageContent">

                <h3>Offene Filme</h3>
                <ul class="collection">

                    <?php foreach($films as $id => $film){ if($film['status'] != "requested"){continue;}?>

                    <li class="collection-item avatar">
                        <i class="material-icons circle red tooltipped" data-position="right" data-tooltip="<?php echo "IP: ".$film['requestIp']."<br />Zeit: ".date('H:i d/m/Y', $film['requestTime'])."<br />Email: ".$film['email'] ?>">new_releases</i>
                      <span class="title"><?php echo $film['filmtitel'] ?></span>
                      <p><?php echo  $film["Fbeschreibung"] ?></p>
                      <p>Download Link: <?php echo  "<a target='_blank' href=".$film['downloadLink'] . ">" . $film["downloadLink"] . "</a>"?></p>
                      <a href="#!" class="secondary-content"><i class="material-icons" onclick="setfStatus('<?php echo $id ?>','deleted')">delete_forever</i><i class="material-icons" onclick="setfStatus('<?php echo $id ?>','downloading')">keyboard_arrow_down</i></a>
                    </li>

                    <?php } ?>
                </ul>

                <h3>Herunterladende Filme</h3>
                <ul class="collection">

                    <?php foreach($films as $id => $film){ if($film['status'] != "downloading"){continue;}?>

                    <li class="collection-item avatar">
                        <i class="material-icons circle yellow tooltipped" data-position="right" data-tooltip="<?php echo "IP: ".$film['requestIp']."<br />Zeit: ".date('H:i d/m/Y', $film['requestTime'])."<br />Email: ".$film['email'] ?>">file_download</i>
                      <span class="title"><?php echo $film['filmtitel'] ?></span>
                      <p><?php echo  $film["Fbeschreibung"] ?></p>
                      <a href="#!" class="secondary-content"><i class="material-icons" onclick="setfStatus('<?php echo $id ?>','requested')">keyboard_arrow_up</i><i class="material-icons" onclick="setfStatus('<?php echo $id ?>','finished')">keyboard_arrow_down</i></a>
                    </li>

                    <?php } ?>
                </ul>

                <h3>Fertige Filme</h3>
                <ul class="collection">

                    <?php foreach($films as $id => $film){ if($film['status'] != "finished"){continue;}?>

                    <li class="collection-item avatar">
                        <i class="material-icons circle green tooltipped" data-position="right" data-tooltip="<?php echo "IP: ".$film['requestIp']."<br />Zeit: ".date('H:i d/m/Y', $film['requestTime'])."<br />Email: ".$film['email'] ?>">offline_pin</i>
                      <span class="title"><?php echo $film['filmtitel'] ?></span>
                      <p><?php echo  $film["Fbeschreibung"] ?></p>
                      <a href="#!" class="secondary-content"><i class="material-icons" onclick="setfStatus('<?php echo $id ?>','downloading')">keyboard_arrow_up</i></a>
                    </li>

                    <?php } ?>
                </ul>

            </ul>
          </div>

          <!-- Filme Tab -->
          <div id="nserien" href="#nserien" class="tab-pane fade in" role="tabpanel">

            <ul id="pageContent">

                <h3>Offene Serien</h3>
                <ul class="collection">

                    <?php foreach($series as $id => $serie){ if($serie['status'] != "requested"){continue;}?>

                    <li class="collection-item avatar">
                        <i class="material-icons circle red tooltipped" data-position="right" data-tooltip="<?php echo "IP: ".$serie['requestIp']."<br />Zeit: ".date('H:i d/m/Y', $serie['requestTime'])."<br />Email: ".$serie['email'] ?>">new_releases</i>
                      <span class="title"><?php echo $serie['serientitel'] ?> - Staffel <?php echo $serie['staffel'] ?></span>
                      <p><?php echo  $serie["Sbeschreibung"] ?></p>
                      <a href="#!" class="secondary-content"><i class="material-icons" onclick="setsStatus('<?php echo $id ?>','deleted')">delete_forever</i><i class="material-icons" onclick="setsStatus('<?php echo $id ?>','downloading')">keyboard_arrow_down</i></a>
                    </li>

                    <?php } ?>
                </ul>

                <h3>Herunterladende Serien</h3>
                <ul class="collection">

                    <?php foreach($series as $id => $serie){ if($serie['status'] != "downloading"){continue;}?>

                    <li class="collection-item avatar">
                        <i class="material-icons circle yellow tooltipped" data-position="right" data-tooltip="<?php echo "IP: ".$serie['requestIp']."<br />Zeit: ".date('H:i d/m/Y', $serie['requestTime'])."<br />Email: ".$serie['email'] ?>">file_download</i>
                      <span class="title"><?php echo $serie['serientitel'] ?> - Staffel <?php echo $serie['staffel'] ?></span>
                      <p><?php echo  $serie["Sbeschreibung"] ?></p>
                      <a href="#!" class="secondary-content"><i class="material-icons" onclick="setsStatus('<?php echo $id ?>','requested')">keyboard_arrow_up</i><i class="material-icons" onclick="setsStatus('<?php echo $id ?>','finished')">keyboard_arrow_down</i></a>
                    </li>

                    <?php } ?>
                </ul>

                <h3>Fertige Serien</h3>
                <ul class="collection">

                    <?php foreach($series as $id => $serie){ if($serie['status'] != "finished"){continue;}?>

                    <li class="collection-item avatar">
                        <i class="material-icons circle green tooltipped" data-position="right" data-tooltip="<?php echo "IP: ".$serie['requestIp']."<br />Zeit: ".date('H:i d/m/Y', $serie['requestTime'])."<br />Email: ".$serie['email'] ?>">offline_pin</i>
                      <span class="title"><?php echo $serie['serientitel'] ?> - Staffel <?php echo $serie['staffel'] ?></span>
                      <p><?php echo  $serie["Sbeschreibung"] ?></p>
                      <a href="#!" class="secondary-content"><i class="material-icons" onclick="setsStatus('<?php echo $id ?>','downloading')">keyboard_arrow_up</i></a>
                    </li>

                    <?php } ?>
                </ul>

            </ul>


          </div>

      </div>






        <!--JavaScript at end of body for optimized loading-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <!-- SweetAlert 2 Javascript -->
				<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <!-- own functions js -->
			  <script src="../js/functions.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.tooltipped');
        var instances = M.Tooltip.init(elems);
        });
        </script>

        <script type="text/javascript">
            $("#formular-button").click(function(){
              window.location.href = "../request/";
            });
        </script>

        <!-- get last opened tab -->
        <script type="text/javascript">
        $(document).ready(function(){
            $('a[data-toggle="pill"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

            // check Admin Mail
  					checkAdminMail();

            // check for Update Button
            $("#update-button").click(function(){
              $.ajax({
                type: "POST",
                url: "version.php",
                data: { token: "<?php $sitetoken = json_decode(file_get_contents("../data/config.json"), true); echo $sitetoken['admin']['token'] ?>" }
              }).done(function( data ) {

                // get data array from response
                var data = $.parseJSON(data);

                  if( data.status == 'update' ) {

                    window.location.href = '../updatever.php?ver=' + data.version + '&t=<?php $sitetoken = json_decode(file_get_contents("../data/config.json"), true); echo $sitetoken['admin']['token'] ?>';

                  } else {

                      alert("PlexAsk ist auf Version " + data.version + " und somit aktuell!");

                  }

              });
            });

        });
        </script>

        <script>

          function setfStatus(id, status){

              console.log([id, status]);
              $.ajax({
                  method: "post",
                  url: "../functions/setStatus.php",
                  data: {
                      token: "<?php $sitetoken = json_decode(file_get_contents("../data/config.json"), true); echo $sitetoken['admin']['token'] ?>",
                      id: id,
                      status: status
                  },
                  success: function(result){
                      location.reload();
                  }
              });

          }

          function setsStatus(id, status){

            console.log([id, status]);
            $.ajax({
                method: "post",
                url: "../functions/setseriesStatus.php",
                data: {
                    token: "<?php $sitetoken = json_decode(file_get_contents("../data/config.json"), true); echo $sitetoken['admin']['token'] ?>",
                    id: id,
                    status: status
                },
                success: function(result){
                    location.reload();
                }
            });

          }

          // check Admin Mail
					function checkAdminMail(){
						$.ajax({
                method: "post",
                url: "../functions/functions.php",
								data: {
									action: "checkAdminMail"
								},
                success: function(result){
									// get data array from response
                  var data = $.parseJSON(result);
									if(data.mailSet == false) {

                    enterAdminMail();

									}
								},
								error: function(result){
								console.log("Konnte Admin Mail nicht überprüfen");
							}
						});
					}

          // ask for admin Mail function()
          async function enterAdminMail() {

            const { value: email } = await Swal.fire({
              title: 'Admin Email angeben',
              text: 'Bitte gebe die Mailadresse des Admin Accounts an (Gleiche Mail wie bei Plex Account)',
              input: 'email',
              inputPlaceholder: 'Email Adresse angeben',
              confirmButtonText: "Speichern!"
            })
            // when email is set
            if (email) {
              writeToJson("admin", "email", email.toLowerCase());
              Swal.fire({
                title: 'Email gespeichert!',
                text: 'Email: ' + email.toLowerCase() + ' erfolgreich gespeichert.'
              });
            }

          }


        </script>

    </body>
</html>
