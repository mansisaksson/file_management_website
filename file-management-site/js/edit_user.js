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
		var formElement = document.forms.namedItem("userForm");
		var form_Data = new FormData(formElement);
		
	    $.ajax
	    ({
	        url: RP_PHP_SCRIPTS_DIR + 'edit_user.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(response) {
				if (response.success) {
					alert("User Updated Successfully!");
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
		
		var fileId = document.getElementById('user_id').value;
		form_Data.append("user_id", fileId);
		
	    $.ajax({
	        url: RP_PHP_SCRIPTS_DIR+'delete_user.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
				if (response.success) {
					alert("User Removed!");
					window.location.replace("index.php");
				} else {
					alert(response.message);
				}
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		alert(errorThrown);
	    	}
	    });
	});
});
