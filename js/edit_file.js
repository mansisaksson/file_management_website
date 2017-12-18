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
	        url: RP_PHP_DIR+'edit_file.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response){
	            document.getElementById("editReturn").innerHTML = php_script_response;
	        }
	    });
	});
	
	$('#delete').on('click', function() {
		var password = prompt("Enter Password", "");
		alert("TODO...");
		var form_Data = new FormData();
		
//	    $.ajax({
//	        url: RP_PHP_DIR+'delete_file.php',
//	        type: 'POST',
//	        data: form_Data,
//	        cache: false,
//	        contentType: false,
//	        processData: false,
//	        
//	        success: function(php_script_response) {
//	            document.getElementById("uploadReturn").innerHTML = php_script_response;
//	        }
//	    });
	});
});
