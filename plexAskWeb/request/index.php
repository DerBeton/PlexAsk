<?php
session_start();
// to clean every output before redirect
ob_start();
?>

<?php

if (!isset($_SESSION['userToken'])) {

  // now clean output
	ob_end_clean();
  header('location: ../login');

}

// Get Plex Films: http://IP:Port/library/sections/1/all?X-Plex-Token={TOKEN}
// Get Plex IP: https://plex.tv/pms/resources/?X-Plex-Token={TOKEN}&includeHttps=1

// Check htaccess for security
if(!is_file("../data/.htaccess")){
  file_put_contents("../data/.htaccess", "Order allow,deny\nDeny from all");
}

?>
<!DOCTYPE html>
<html lang="de">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Raymond Movie Requests</title>
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <!-- include own css -->
        <link href="../css/style.css" type="text/css" rel="stylesheet" />
				<link href="../customize.css" type="text/css" rel="stylesheet" />
				<!-- include the google rechaptcha script -->
				<script src='https://www.google.com/recaptcha/api.js'></script>
				<!-- include vue.js :) -->
				<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
				<!-- Include own js -->
	   		<script src="../js/script.js"></script>
				<!-- Set tab logo -->
				<link rel="db icon" href="../css/img/DB.png"/>
  	   	<script>

  				// Page loader
  				var pTimeout;

  				function loadLoader() {
  						pTimeout = setTimeout(showPage, 100);
  				}

  				function showPage() {
  					document.getElementById("loader").style.display = "none";
  					document.getElementById("pageContent").style.display = "inline";
  				}

      	</script>

    </head>

    <body onload="loadLoader()" id="backgroundImage">

			<div id="loader"></div>

			<div style="display:none;" id="pageContent">


				<div id="filmWunsch">


						<!-- Ceate Tabs -->
						<nav class="nav nav-pills nav-fill">
							<a class="nav-item nav-link" data-toggle="pill" href="#nfilme">Filme</a>
							<a class="nav-item nav-link" data-toggle="pill" href="#nserien">Serien</a>
						</nav>

						<!-- Tab panes -->
						<div class="tab-content">

							<!-- Filme Tab -->
							<div id="nfilme" class="tab-pane fade in">

								<!-- Plex Film-Formular -->
								<h2 class="formtitle">Welchen Film möchtest du auf dem PLE<span>X</span> haben?</h2>
								<p>Hier kannst du den Plex Server mit Filmwünschen anreichern!</p>
									<form id="fcontactForm">
										<input id="filmid" type="hidden" name="filmid" >
										<label for="username">Filmtitel*</label>
										<input id="filmtitel" name="filmtitel" autocomplete="off" type="text" list="filme" required placeholder="Fast & Furious 8" />
										<input id="Fbeschreibung" name="Fbeschreibung" type="text" />
										<label id="Tbeschreibung"></label>
										<p id="f_beschreibung"></p>
										<!-- Insert Google recaptcha -->
										<div id="f-recaptcha">
										</div>
										<!-- Div für Film-Poster -->
										<div id="fthumbnail">

										</div>
										<input type="submit" name="submit" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Request Sending" />
									</form>

								<!-- Datalist für Filmtitel -->
								<datalist id="filme">
										<option value="tippen um Vorschläge zu laden" disabled>
								</datalist>



							</div>


							<!-- Serien Tab -->
							<div role="tabpanel" class="tab-pane fade" id="nserien">

								<!-- recaptcha tutorial https://askjong.com/howto/run-multiple-google-recaptcha-on-the-same-page -->

								<!-- Plex Serien-Formular -->
								<h2 class="formtitle">Welche Serie möchtest du auf dem PLE<span>X</span> haben?</h2>
								<p>Hier kannst du den Plex Server mit Serienwünschen anreichern!</p>
									<form id="seriesform">
										<input id="seriesid" type="hidden" name="sid" >
										<label for="username">Serientitel*</label><br/>
										<input id="serientitel" name="serientitel" autocomplete="off" type="text" list="serien" required placeholder="Haus des Geldes" />
										<label for="staffel">Staffel*</label><br/>
										<input id="staffel" name="staffel" autocomplete="off" type="text" required placeholder="1" />
										<input id="Sbeschreibung" name="Sbeschreibung" type="text" />
										<label id="STbeschreibung"></label>
										<p id="s_beschreibung"></p>
										<!-- Insert Google recaptcha -->
										<div id="s-recaptcha">
											<div class="g-recaptcha" data-sitekey=<?php $sitekey = json_decode(file_get_contents("../data/config.json"), true); echo $sitekey['recaptcha']['sitekey'] ?>></div>
										</div>
										<!-- Div für Film-Poster -->
										<div id="sthumbnail">

										</div>
										<input type="submit" name="submit" id="ssubmit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Request Sending" />
									</form>

								<!-- Datalist für Serientitel -->
								<datalist id="serien">
										<option value="tippen um Vorschläge zu laden" disabled>
								</datalist>

							</div>





						</div>

					<!-- Modal -->
				<div class="modal fade" id="requestStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Anfrage Status</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div id="requestoutput">
										{{ message }}
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" id="modalclose" data-dismiss="modal">Schliessen</button>
								</div>
							</div>
						</div>
					</div>



        </div>




			</div>


      	<!-- Beginn der Scripts -->
        <script>
				"use strict";




			// Get activated Tab
			$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
				var tabtype = $(e.target).attr("href") // activated tab
				var formtype;

				if (tabtype == "#nserien") {
					formtype = "#serientitel"

					// Variable für <datalist> und <input> Element definieren.
					var dataList = document.getElementById('serien');
					var input = document.getElementById('serientitel');
					var Sbeschreibung = document.getElementById('Sbeschreibung');

					$(".g-recaptcha").appendTo("#s-recaptcha");


				} else if (tabtype == "#nfilme") {
					formtype = "#filmtitel"

					// Variable für <datalist> und <input> Element definieren.
					var dataList = document.getElementById('filme');
					var input = document.getElementById('filmtitel');
					var Fbeschreibung = document.getElementById('Fbeschreibung');

					$(".g-recaptcha").appendTo("#f-recaptcha");

				}

				//######################################################//
								// Script für Filmanfragen //
				//######################################################//


				function dbrequest(succ) {

						// Var film als Input von Filmtitel definieren
					var inputValue = $(formtype).val();
					// alle bisherigen Datalist-Optionen löschen (Sonst sind Einträge mehrfach drin)
					$(".filmOption").remove();


					// Variable settings für die "The Movie Database" abfrage
					if (formtype == "#filmtitel") {

						var settings = {
							"async": true,
							"crossDomain": true,
							"dataType": 'json',
							"url": "https://api.themoviedb.org/3/search/movie?api_key=<?php $tmdb = json_decode(file_get_contents("../data/config.json"), true); echo $tmdb['tmdb']['token'] ?>&query=" + inputValue + "&language=de",
							"method": "GET",
							"headers": {},
							"data": JSON.stringify(settings)
						}

								// Sobald mindestens ein Zeichen in die Suche eingetragen wurde:
								var laenge = inputValue.length;
								//var gerade = laenge % 2;

								if(laenge > 0) {

										// Abfrage der Datenbank response als Antwort
										$.ajax(settings).done(function (response) {

										// Placeholder von Input anpassen
										input.placeholder = "Search your film";

																// Var filme als Resultate der der Antwort
										var filme = response.results;

																// Für jeden Film eine Option zur Datalist hinzufügen
										$.each(filme, function(title,value) {

											// Neues <option> Element erstellen.
											var option = document.createElement('option');
											// Value von <option> aus JSON setzen
											option.value = value.title;
											// <option> Element der <datalist> hinzufügen.
											// <option> Element die Klasse "filmOption" geben (fürs Löschen).
											option.className = "filmOption";
											// <option> Element der <datalist> hinzufügen.
											dataList.appendChild(option);

										});
									})
								}

					} else if (formtype == "#serientitel") {

						// serien nummern herausfinden: https://api.themoviedb.org/3/tv/1399?api_key={API-KEY}append_to_response=number_of_seasons
						// muss aber mit eindeutiger ID geschehen

						var settings = {
							"async": true,
							"crossDomain": true,
							"dataType": 'json',
							"url": "https://api.themoviedb.org/3/search/tv?api_key=<?php $tmdb = json_decode(file_get_contents("../data/config.json"), true); echo $tmdb['tmdb']['token'] ?>&query=" + inputValue + "&language=de",
							"method": "GET",
							"headers": {},
							"data": JSON.stringify(settings)
						}


								// Sobald mindestens ein Zeichen in die Suche eingetragen wurde:
								if(inputValue.length > 0) {

										// Abfrage der Datenbank response als Antwort
										$.ajax(settings).done(function (response) {

										// Placeholder von Input anpassen
										input.placeholder = "Search your series";

										// Var series als Resultate der der Antwort
										var series = response.results;

										// Für jede Serie eine Option zur Datalist hinzufügen
										$.each(series, function(title,value) {

											// Neues <option> Element erstellen.
											var option = document.createElement('option');
											// Value von <option> aus JSON setzen
											option.value = value.name;
											// <option> Element der <datalist> hinzufügen.
											// <option> Element die Klasse "filmOption" geben (fürs Löschen).
											option.className = "filmOption";
											// <option> Element der <datalist> hinzufügen.
											dataList.appendChild(option);

												});
											})
										}



					}



				}


				var timeout = null;

				// Ausführen, sobald ins Textfeld getippt wird
				$(formtype).on("keydown", function() {

					clearTimeout(timeout);

					// Erst wenn nicht mehr geschrieben wird ausführen
					timeout = setTimeout(function () {
							dbrequest();
					}, 500); // 500ms nach dem Tippen ausführen



				});

			});



			// Ausführen, sobald die Suche "filmtitel" verlassen wird.
			$("input[id=filmtitel]").focusout(function(){
				// Var devFilmTitel als ausgewählter Film definieren.
				var devFilmTitel = $(this).val();


              // Variable settings für die "The Movie Database" abfrage
							var devFilmSettings = {
									"async": true,
									"crossDomain": true,
									"dataType": 'json',
									"url": "https://api.themoviedb.org/3/search/movie?api_key=<?php $tmdb = json_decode(file_get_contents("../data/config.json"), true); echo $tmdb['tmdb']['token'] ?>&query=" + devFilmTitel + "&language=de",
									"method": "GET",
									"headers": {},
									"data": JSON.stringify(devFilmSettings)
							}

							if(devFilmTitel.length > 0) {

								// Abfrage der Datenbank response als Antwort
								$.ajax(devFilmSettings).done(function (response) {

									var devFilm = response.results[0];
													$("#mPoster").remove();

													$("#filmid").val(devFilm.id);

													//alert(value.title);
													$('#Tbeschreibung').html("Filmbeschreibung");
													$('#f_beschreibung').html(devFilm.overview);
													var thumbnail = "https://image.tmdb.org/t/p/w500/" + devFilm.poster_path;

													var poster = $("<img class='poster' id='mPoster' src=" + thumbnail + "></img>");
													$("#fthumbnail").append(poster);

													// Beschreibung für E-Mail definieren
													var beschreibung_final = $('#f_beschreibung').text();

											Fbeschreibung.value = beschreibung_final;


											});

							}

						});



						// Ausführen, sobald die Suche "serientitel" verlassen wird.
						$("input[id=serientitel]").focusout(function(){
							// Var devFilmTitel als ausgewählter Film definieren.
							var devSerienTitel = $(this).val();


						// Variable settings für die "The Movie Database" abfrage
										var devSerienSettings = {
												"async": true,
												"crossDomain": true,
												"dataType": 'json',
												"url": "https://api.themoviedb.org/3/search/tv?api_key=<?php $tmdb = json_decode(file_get_contents("../data/config.json"), true); echo $tmdb['tmdb']['token'] ?>&query=" + devSerienTitel + "&language=de",
												"method": "GET",
												"headers": {},
												"data": JSON.stringify(devSerienSettings)
										}

										if(devSerienTitel.length > 0) {

											// Abfrage der Datenbank response als Antwort
											$.ajax(devSerienSettings).done(function (response) {

												var devSerie = response.results[0];
																$("#sPoster").remove();

																$("#seriesid").val(devSerie.id);

																//alert(value.title);
																$('#STbeschreibung').html("Serienbeschreibung");
																$('#s_beschreibung').html(devSerie.overview);
																var thumbnail = "https://image.tmdb.org/t/p/w500/" + devSerie.poster_path;

																var poster = $("<img class='poster' id='sPoster' src=" + thumbnail + "></img>");
																$("#sthumbnail").append(poster);

																// Beschreibung für E-Mail definieren
																var beschreibung_final = $('#s_beschreibung').text();

																Sbeschreibung.value = beschreibung_final;


														});

										}

									});
			</script>


    </body>


</html>
