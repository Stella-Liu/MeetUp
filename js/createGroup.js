// Get the modal
var modal = document.getElementById('modalCreate');

// Get the button that opens the modal
var btn = document.getElementById("createGroup");

// Get the button that closes the modal
var cancelBtn = document.getElementById("cancelCreate");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
    console.log("open");
}

// When the user clicks the button, close the modal 
cancelBtn.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

var request;

$(function() {
	$("#create_group").on("submit", function(event) {
		// Stop the browser from submitting the form.
		event.preventDefault();

		console.log("submit");
		var $form = $("#create_group");

		// Serialize the form data.
		var formData = $(this).serializeArray();

		// Submit the form using AJAX.
		request = $.ajax({
		    type: 'POST',
		    url: "http://localhost/webDevProject/html/my_groups.php",
		    data: formData
		})

	   	// Callback handler that will be called on success
		request.done(function(response, textStatus, jqXHR) {
		    // Log a message to the console
		    console.log("Hooray, it worked!");

		    // Clear the form.
		    $('#group_name').val('');
		    $('#group_interest').val('');
		    $('#description').val('');
		});

	    // Callback handler that will be called on failure
	    request.fail(function (jqXHR, textStatus, errorThrown){
	        // Log the error to the console
	        console.error(
	            "The following error occurred: "+
	            textStatus, errorThrown
	        );		    
	    });
	});

	//Listen to button press
	$("#submit_group").on('click', function(){
		console.log("clicked");
		// Set up an event listener for the contact form.
		$("#create_group").submit();
		modal.style.display = "none";
	});
});
