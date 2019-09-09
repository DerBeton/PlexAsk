// file for plex Login
function pressedBtnPlexLogin(clientId) {


  $.post('https://plex.tv/api/v2/pins.json?strong=true',
			{
				'X-Plex-Product': 'PlexAsk',
				'X-Plex-Platform': 'Web',
				'X-Plex-Device': 'Name (Web)',
				'X-Plex-Client-Identifier': clientId
			},
			function(data, status){
				//console.log(print_r(data));

					if(data === "") {

						alert("Anfrage für Plex login konnte nicht gestartet werden...");

					} else {

            var baseUrl = window.location.href;
            baseUrl = baseUrl.substring(0, baseUrl.length -1); // removove last / from url


						url = "https://app.plex.tv/auth/#!?context[device][product]=" + "PlexAsk" + "&context[device][environment]=bundled&context[device][layout]=desktop" + "&context[device][platform]=" + "Web" + "&context[device][device]=" + "Name (Web)" + "&clientID=" + data['clientIdentifier'] + "&forwardUrl=" + baseUrl + "?plex=" + data['id'] + "&code=" + data['code'];
						window.location.replace(url);

						//$('#append').text(JSON.stringify(data));

						//https://app.plex.tv/auth#?context[device][product]=PlexAsk&context[device][environment]=bundled&context[device][layout]=mobile&context[device][platform]=Android&context[device][device]=Plex%20Web&clientID=d2e126egwdawhudhaw2&forwardUrl=https://plexask.derbeton.ch/plexAsk?t=103449891&code=B6S7
						//https://app.plex.tv/auth#?context[device][product]=Raymond&context[device][environment]=bundled&context[device][layout]=desktop&context[device][platform]=Web&context[device][device]=Name%20(Web)&clientID=d2e126egedq67dg126&forwardUrl=https://plexask.derbeton.ch/mobile/login?plex=1128679183&code=invzc8hmcbgsgtwnfi9z5c7qh


					}


  });

}
