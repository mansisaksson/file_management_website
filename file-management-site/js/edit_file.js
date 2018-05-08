function togglePasswordFormEnabled()
{
	var enabled = document.getElementById('change_password').checked;
	var passwordInput = document.getElementById("new_password");
	var confPasswordInput = document.getElementById("password_confirm");

	passwordInput.disabled = !enabled;
	confPasswordInput.disabled = !enabled;
}

require(["lib/jquery.min"], function() {
	$('#apply').on('click', function() {
		var formElement = document.forms.namedItem("fileForm");
		var form_Data = new FormData(formElement);
		
	    $.ajax
	    ({
	        url: RP_PHP_SCRIPTS_DIR + 'edit_file.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(response) {
				if (response.success) {
					var stay = !confirm("File Edited Successfully, go back to files?");
					if (!stay) {
						window.location.replace("index.php?content=file_overview.php");	
					}
				} else {
					alert(response.message);
				}
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		alert(errorThrown);
	    	}
	    });
	});
	
	$('#delete').on('click', function() {
		var password = prompt("Enter Password", "");
		if (password == null || password == "")
			return;
		
		var form_Data = new FormData();
		form_Data.append("password", password);
		
		var fileId = document.getElementById('file_id').value;
		form_Data.append("file_id", fileId);
		
	    $.ajax({
	        url: RP_PHP_SCRIPTS_DIR + 'delete_file.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(response) {
				if (response.success) {
					alert("File Removed!");
					window.location.replace("index.php?content=file_overview.php");
				} else {
					returnElement.innerHTML = response.message;
				}
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		alert(errorThrown);
	    	}
	    });
	});
});
