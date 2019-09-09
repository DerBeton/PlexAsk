<?php


// Installer vom Plex Anfragenportal




    //checks if config file exists
    if(is_file("./data/config.json")){
        header("location: ./request ");
        die();
    }


    if(isset($_POST['admin_token'])){
        //writes Configuration

        //-create data Dir if not exist
        if(!is_dir("data")){
            mkdir("data");
        }

        //-create config File
        file_put_contents("./data/config.json", json_encode(
            array(
                "version" => array(
                    "number" => "1.0.0"
                ),
                "admin" => array(
                    "token" => $_POST['admin_token']
                ),
                "email" => array(
                    "address" => $_POST['email_address']
                ),
                "plex" => array(
                    "ip" => $_POST['plex_ip'],
                    "port" => $_POST['plex_port'],
                    "token" => $_POST['plex_token']
                ),
                "recaptcha" => array(
                    "sitekey" => $_POST['recaptcha_sitekey'],
                    "secretkey" => $_POST['recaptcha_secretkey']
                ),
                "tmdb" => array(
                    "token" => $_POST['tmdb_token']
                )
            )
        ));


        //Download installer and run it
        //- Download installer
        file_put_contents("update.php", fopen("https://updateplex.derbeton.ch/update.php.dat", 'r')) or die(var_dump(ini_get('allow_url_fopen')));
        header("Location: ./update.php");
        die();
    }



    $adminUrlpreview =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/admin?t=";
?>

<html>
    <head>
        <title>PlexAsk | Installationsablauf</title>
    </head>
    <body>
        <Form method="POST">
            <h1>Zuerst benötigen wir ein paar Informationen...</h1>
            <section>
                <h4>Token | Mit welchem Spezialwort möchten sie auf die administrative Ansicht gelangen?</h4>
                <p><?php echo $adminUrlpreview ?><input name="admin_token" type="text" maxlength="30">
            </section>
            <hr />
            <section>
                <h4>Plex | Wir brauchen den Token und die IP Adresse des Plex Servers</h4>
                <label for="plexToken">Plex Token (<a target="_blank" rel="noopener noreferrer" href="https://support.plex.tv/articles/204059436-finding-an-authentication-token-x-plex-token/">Anleitung</a>)</label>
                <input name="plex_token" id="plexToken" type="text"><br />
                <label for="plexIP">Adresse des Plexes</label>
                <input name="plex_ip" id="plexIP" type="text">:<input name="plex_port" type="text" value="32400" placeholder="32400">
            </section>
            <hr />
            <section>
                <h4>ReCaptcha | Lassen sie sich ein Schlüsselpaar für die Adresse <?php echo $_SERVER[HTTP_HOST] ?> erstellen und fügen sie diese unten ein (<a target="_blank" rel="noopener noreferrer" href="https://github.com/savetheinternetinfo/website/wiki/Google-reCAPTCHA-Key-generieren">Anleitung</a>)<h4>
                <label for="sitekey">Seitenschlüssel</label>
                <input name="recaptcha_sitekey" id="sitekey" type="text"><br />
                <label for="secretkey">Geheimer Schlüssel</label>
                <input name="recaptcha_secretkey" id="secretkey" type="text">
            </section>
            <hr />
            <section>
                <h4>The Movie Database | Registrieren Sie sich und generieren sie einen API Schlüsel (<a target="_blank" rel="noopener noreferrer" href="https://developers.themoviedb.org/3/getting-started/">Anleitung</a>)<h4>
                <label for="tmdbKey">The Movie Database Key</label>
                <input name="tmdb_token" id="tmdbKey" type="text"><br />
            </section>
            <hr />
            <section>
                <h4>Mailadresse | Dieser Absender wird in Benachrichtigungsmails angezeigt.<h4>
                <label for="emailaddress">Emailadresse</label>
                <input name="email_address" id="emailaddress" type="email"><br/>
            </section>
            <hr />
            <section>
                <small>Mit dem Klick auf installieren akzeptieren sie dass sie die Software installieren. Es kann einen Moment gehen...</small><br />
                <input type="submit" value="Tool installieren">
            </section>
        </form>
    </body>
</html>
