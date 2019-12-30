<?php
session_start();

require "../functions/authCheck.php";

if (editSettings() !== true) {
  header('Location: ../admin/');
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
			<link href="../css/settings.css" type="text/css" rel="stylesheet" />
			<!-- Set tab logo -->
			<link rel="db icon" href="../css/img/DB.png"/>
			<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
			<meta charset="utf-8"/>
			<title>Raymond</title>
    </head>
    <body>

      <!-- Panel Button -->
      <div id="d-formular">
          <input class="action-button" id="panel-button" type="button" value="Panel" />
      </div>

			<div id="d_pa_settings">
				<div id="pa_settings">
					<div id="d_pa_settings_version">
						<span class="pa_p_version">Ver.&nbsp<span id="pa_p_version" class="pa_p_version"><?php echo $config['version']['number']; ?></span></span>
					</div>
				<h3 class="pa_h3">Einstellungen</h3>
				<div id="pa_form">
					<div class="form-group">
						<label class="pa_label" for="pa_input_a_mail">Admin Mail:</label>
						<input class="pa_input" id="pa_input_a_mail" name="pa_input_a_mail" type="text" value="<?php echo $config['admin']['email']; ?>" \>
					</div>
					<div class="form-group">
						<label class="pa_label" for="pa_input_accounts">Adminpanel Berechtigte Accounts</label>
						<input class="pa_input" id="pa_input_accounts" name="pa_input_accounts" value="<?php echo $config['admin']['email']; ?>" type="text"></input>
            <input class="pa_input pa_submit" onclick="userRights.addUser()" id="pa_user_submit" name="pa_user_submit" type="submit" value="Add!"></input>
            <div id="d_user_rights">
              <table>
                <thead>
                  <tr>
                    <th>Email</th>
                    <th>Admin Panel</th>
                    <th>Edit Settings</th>
                  </tr>
                </thead>
                <tbody class="pa_tbody">
                <tr class="d_users" v-for="(user, index) in users">
                  <!-- <h1 v-if="item.editSettings == true">Vue is awesome!</h1> -->
                  <th><p class="pa_p_user-mail">{{ user.email }}</p></th>
                  <th>
                    <label class="pa_label l_checkbox">
                    <input class="pa-checkbox" v-on:click="changeRights(index, 1)" id="pa-checkbox_admin-panel" name="pa-checkbox_admin-panel" type="checkbox" v-bind="{ checked: user.adminPanel }">
                    <span class="sp_checkbox"></span>
                    </label>
                  </th>
                  <th>
                    <label class="pa_label l_checkbox">
                    <input class="pa-checkbox" v-on:click="changeRights(index, 2)" id="pa-checkbox_edit-settings" name="pa-checkbox_edit-settings" type="checkbox" v-bind="{ checked: user.editSettings }">
                    <span class="sp_checkbox"></span>
                    </label>
                  </th>
                  <th>
                    <i v-on:click="deleteUser(index)" class="material-icons pa_icon_delete">delete_forever</i>
                  </th>
                </tr>
                </tbody>
              </table>
            </div> <!-- end d_user_rights -->
					</div>
					<div class="form-group">
						<label class="pa_label" for="pa_input_recaptcha_sitekey">Recaptchav2 Sitekey</label>
						<input class="pa_input" id="pa_input_recaptcha_sitekey" name="pa_input_recaptcha_sitekey" type="text" value="<?php echo $config['recaptcha']['sitekey']; ?>" \>
					</div>
					<div class="form-group">
						<label class="pa_label" for="pa_input_recaptcha_secretkey">Recaptchav2 Secretkey</label>
						<input class="pa_input" id="pa_input_recaptcha_secretkey" name="pa_input_recaptcha_secretkey" type="text" value="<?php echo $config['recaptcha']['secretkey']; ?>" \>
					</div>
					<div class="form-group">
						<label class="pa_label" for="pa_input_tmdb">TMDb Token</label>
						<input class="pa_input" id="pa_input_tmdb" name="pa_input_tmdb" type="text" value="<?php echo $config['tmdb']['token']; ?>" \>
					</div>

					<div class="form-group">
						<label class="pa_label" for="pa_input_a_token">PlexAsk Adminpanel Token</label>
						<input class="pa_input" id="pa_input_a_token" name="pa_input_a_token" type="text" value="<?php echo $config['admin']['token']; ?>" \>
					</div>

					<div class="form-group">
						<label class="pa_label" for="pa_input_plex_ip">Plex Server IP : Port</label>
						<input class="pa_input" id="pa_input_plex_ip" name="pa_input_plex_ip" type="text" value="<?php echo $config['plex']['ip']; ?>" \>
						<span class="pa_input_span">:</span>
						<input class="pa_input" id="pa_input_plex_port" name="pa_input_plex_port" type="text" value="<?php echo $config['plex']['port']; ?>" \>
					</div>

					<div class="form-group">
						<label class="pa_label" for="pa_input_plex_token">Plex Server Token</label>
						<input class="pa_input" id="pa_input_plex_token" name="pa_input_plex_token" type="text" value="<?php echo $config['plex']['token']; ?>" \>
					</div>

					<div class="form-group">
						<input class="pa_input pa_submit" id="pa_form_submit" name="pa_form_submit" type="submit" value="Speichern" \>
					</div>
				</div>
				</div>
			</div>

			<!--JavaScript at end of body for optimized loading-->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<!-- Latest compiled JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
			<!-- SweetAlert Javascript -->
			<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <!-- include vue js -->
      <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
			<!-- own functions js -->
			<script src="../js/functions.js"></script>
			<!-- own ajax functions js -->
			<script src="../js/ajax.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
      <!-- own settings js -->
      <script src="../js/settings.js"></script>

      <!-- on panel Button pressed -->
      <script type="text/javascript">
          $("#panel-button").click(function(){
            window.location.href = "../admin/";
          });
      </script>

			<script>

				$('#pa_form_submit').click(function() {

					saveSettings();

				});


				function saveSettings(){

          var versionNumber = $('#pa_p_version').text();
          var adminEmail = $('#pa_input_a_mail').val();
          var recaptchaSitekey = $('#pa_input_recaptcha_sitekey').val();
          var recaptchaSecretkey = $('#pa_input_recaptcha_secretkey').val();
          var tmdbToken = $('#pa_input_tmdb').val();
          var adminToken = $('#pa_input_a_token').val();
          var plexIp = $('#pa_input_plex_ip').val();
          var plexPort = $('#pa_input_plex_port').val();
          var plexToken = $('#pa_input_plex_token').val();

					var objectRaw = '{"version":{"number":"' + versionNumber + '"},"admin":{"token":"' + adminToken + '","email":"' + adminEmail + '"},"plex":{"ip":"' + plexIp + '","port":"' + plexPort + '","token":"' + plexToken + '"},"recaptcha":{"sitekey":"' + recaptchaSitekey + '","secretkey":"' + recaptchaSecretkey + '"},"tmdb":{"token":"' + tmdbToken + '"},"users":' + userRights.getObject() + '}';

          //console.log(objectRaw);

					var object = JSON.stringify(objectRaw);

          // starte ajax Funktion
          var promise = sendAjaxObj("saveSettings", object);
          promise.done(function(data) {
            data = JSON.stringify(data);
            var response = $.parseJSON(data);
            console.log(response.saved);

            if(response.saved == true) {
              Swal.fire({
                title: "Einstellungen Status",
                text: 'Einstellungen konnten erfolgreich gespeichert werden!',
              })
            } else {
              Swal.fire({
                title: "Einstellungen Status",
                text: 'Einstellungen konnten leider nicht gespeichert werden!',
              })
            }

          });
          // Wenn Ajax call Fehlschlägt
          promise.fail(function(data) {
            console.log("Einstellungen konnten nicht gespeichert werden!");
          });

				} //end saveSettings()



			</script>

    </body>
</html>
