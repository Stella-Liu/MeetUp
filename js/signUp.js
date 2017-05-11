var request;

$(function() {
    // Set up an event listener for the contact form.
	$("#signUp").submit(function(event) {
	    // Stop the browser from submitting the form.
	    event.preventDefault();
		var $form = $(this);

	    var inputs = $form.find("username, password, confirm_pass, fname, lname, zipcode");

	    // Serialize the form data.
		var formData = $form.serialize();

		$inputs.prop("disabled", true);

		// Submit the form using AJAX.
		request = $.ajax({
		    type: 'POST',
		    url: "http://localhost/webDevProject/html/signUp.php",
		    data: formData
		})

   		 // Callback handler that will be called on success
		request.done(function(response, textStatus, jqXHR) {
		    // Log a message to the console
	        console.log("Hooray, it worked!");

		    // Clear the form.
		    $('#username').val('');
		    $('#password').val('');
		    $('#confirm_pass').val('');
		    $('#fname').val('');
		    $('#lname').val('');
		    $('#zipcode').val('');
		});

	    // Callback handler that will be called on failure
	    request.fail(function (jqXHR, textStatus, errorThrown){
	        // Log the error to the console
	        console.error(
	            "The following error occurred: "+
	            textStatus, errorThrown
	        );
	    });

	    // Callback handler that will be called regardless
	    // if the request failed or succeeded
	    request.always(function () {
	        // Reenable the inputs
	        $inputs.prop("disabled", false);
	    });
	});
});
