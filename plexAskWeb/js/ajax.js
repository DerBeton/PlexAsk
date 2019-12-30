function sendAjax(action, array) {
	$.ajax({
		type: "POST",
		url: "../functions/ajaxFunctions.php",
		data: {
			action: action,
			data: array
		},
		success: function(data) {
			swal("Worked!");
		}
	});
}


function sendAjaxObj(action, object, path = "../functions/ajaxFunctions.php") {

	return $.ajax({
		type: "POST",
		url: path,
		dataType: "JSON",
		data: {
			action: action,
			data: object
		}
	});

}

function getAjax(action, path = "../functions/ajaxFunctions.php") {
	return $.ajax({
		type: "POST",
		url: path,
		dataType: "JSON",
		data: {
			action: action
		}
	});
}

function deleteUser(index, path = "../functions/ajaxFunctions.php") {
	return $.ajax({
		type: "POST",
		url: path,
		dataType: "JSON",
		data: {
			action: "deleteUser",
			index: index
		}
	});
}
