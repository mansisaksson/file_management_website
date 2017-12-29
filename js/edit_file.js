function togglePasswordFormEnabled()
{
	var enabled = document.getElementById('change_password').checked;
	var passwordInput = document.getElementById("new_password");
	var confPasswordInput = document.getElementById("password_confirm");

	passwordInput.disabled = !enabled;
	confPasswordInput.disabled = !enabled;
}

require(["lib/jquery.min"], function() {
	var returnElement = document.getElementById("php_return");
	
	$('#apply').on('click', function() {
		var formElement = document.forms.namedItem("fileForm");
		var form_Data = new FormData(formElement);
		
	    $.ajax
	    ({
	        url: RP_PHP_DIR+'Scripts/edit_file.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	returnElement.innerHTML = php_script_response;
	    		//alert("File Edited Successfully!");
	    		
	    		var stay = !confirm("File Edited Successfully, go back to files?");
	    		if (!stay) {
	    			window.location.replace("index.php?content=user_overview.php");	
	    		}
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
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
	        url: RP_PHP_DIR+'Scripts/delete_file.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	returnElement.innerHTML = php_script_response;
	    		alert("File Removed!");
	    		window.location.replace("index.php?content=user_overview.php");
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
	
	
	$('#addURL').on('click', function() {
		var formElement = document.forms.namedItem("addURLForm");
		var form_Data = new FormData(formElement);
		
	    $.ajax({
	        url: RP_PHP_DIR+'Scripts/add_onetime_url.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	returnElement.innerHTML = php_script_response;
	    		alert("URL ADDED!");
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
	
	$('.deleteURL').on('click', function() {
		var value = $(this).attr("value");
		var form_Data = new FormData();
		form_Data.append("url_id", value);
		
	    $.ajax({
	        url: RP_PHP_DIR+'Scripts/delete_onetime_url.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	returnElement.innerHTML = php_script_response;
	    		alert("URL REMOVED!");
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
});
