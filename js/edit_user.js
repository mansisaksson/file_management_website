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
	        url: RP_PHP_DIR+'Scripts/edit_user.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	document.getElementById("php_return").innerHTML = php_script_response;
	    		alert("User Edited Successfully!");
	    		window.location.replace("index.php?content=user_overview.php");
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		document.getElementById("php_return").innerHTML = jqXHR.responseText;
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
	        url: RP_PHP_DIR+'Scripts/delete_user.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	document.getElementById("php_return").innerHTML = php_script_response;
	    		alert("User Removed!");
	    		window.location.replace("index.php");
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		document.getElementById("php_return").innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
});
