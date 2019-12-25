// js own functions
$(document).ready(function() {

	// for the collapsable menu
	$('.toggle').click(function(e) {
				e.preventDefault();

				var $this = $(this);
				//var $toggleClass = $this.find('.inner').attr("id");

				if ($this.next().hasClass('show')) {
						$this.next().removeClass('show');
						$this.next().slideUp(350);
				} else {
						$this.parent().parent().find('li .inner').removeClass('show');
						$this.parent().parent().find('li .inner').slideUp(350);
						$this.next().toggleClass('show');
						$this.next().slideToggle(350);

					//var myClass = $this.find('.inner').attr("id");
					//var $class1 = $this.find('div .inner');
					//console.log(myClass);
				}
		});

});

function writeToJson(keyOne, keyTwo, value) {
	$.ajax({
			method: "post",
			url: "../functions/functions.php",
			data: {
				action: "writeToJson",
				keyOne: keyOne,
				keyTwo: keyTwo,
				value: value
			},
			success: function(result){
				var data = $.parseJSON(result);
				// get data array from response
				if(data.mailSaved == true) {
					swal({
						title: "Email gespeichert!",
						text: "Email Adresse wurde erfolgreich als berechtigter Admin Account hinzugefügt.",
						button: "Schliessen",
					});
				} else {
					swal({
						title: "Email nicht gespeichert",
						text: "Email Adresse konnte nicht gespeichert werden.",
						button: "Schliessen",
					});
				}
			},
			error: function(result){
				swal({
					title: "Email nicht gespeichert",
					text: "Email Adresse konnte nicht gespeichert werden.",
					button: "Schliessen",
				});
			}
	});
}
