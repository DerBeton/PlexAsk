// JS for the form send request

	$(document).ready(function() {

		// Variablen definieren

		var request = new Vue({
			el: '#requestoutput',
			data: {
				message: '',
			}
		})

		//######################################################//

		// Film contact Form
		var fcontactForm = $("#fcontactForm");
		//We set our own custom submit function
		fcontactForm.on("submit", function(e) {
			//Prevent the default behavior of a form
			e.preventDefault();



			//Get the values from the form
			var requestTitle =  $("#filmtitel").val();
			var requestBeschreibung = Fbeschreibung.value;
			//var captchaResponse = grecaptcha.getResponse();
			var id = $("#filmid").val();

			//Our AJAX POST
			$.ajax({
				type: "POST",
				url: "validate.php",
				data: {
					type: 'film',
					filmtitel: requestTitle,
					beschreibung: requestBeschreibung,
					id: id,
					//THIS WILL TELL THE FORM IF THE USER IS CAPTCHA VERIFIED.
					captcha: grecaptcha.getResponse(),
				},
				success: function(status) {
					//console.log("OUR FORM SUBMITTED CORRECTLY");

						request.message = status;

						console.log(status);
						$('#requestStatus').modal();
						grecaptcha.reset();

				}
			})
		});

		// Series contact Form
		var scontactForm = $("#seriesform");
		//We set our own custom submit function
		scontactForm.on("submit", function(e) {
			//Prevent the default behavior of a form
			e.preventDefault();



			//Get the values from the form
			var requestTitle = $("#serientitel").val();
			var requestBeschreibung = $("#Sbeschreibung").val();
			//var captchaResponse = grecaptcha.getResponse();
			var id = $("#seriesid").val();
			var staffel = $("#staffel").val();

			//Our AJAX POST
			$.ajax({
				type: "POST",
				url: "validate.php",
				data: {
					type: 'serie',
					serientitel: requestTitle,
					staffel: staffel,
					Sbeschreibung: requestBeschreibung,
					id: id,
					//THIS WILL TELL THE FORM IF THE USER IS CAPTCHA VERIFIED.
					captcha: grecaptcha.getResponse(),
				},
				success: function(status) {
					//console.log("OUR FORM SUBMITTED CORRECTLY");

						request.message = status;

						console.log(status);
						$('#requestStatus').modal();
						grecaptcha.reset();

				}
			})
		});
	});
