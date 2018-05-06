require(["lib/jquery.min"], function() {
    var returnElement = document.getElementById("php_return");
    
	$('#loginButton').on('click', function() {
		var formElement = document.forms.namedItem("user_info_form");
		var form_Data = new FormData(formElement);

        $.ajax({
	        url: RP_PHP_SCRIPTS_DIR+'login.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
            
	        success: function(response) {
                loginResponse = JSON.parse(response);
                
                if (loginResponse.success) {
                    location.replace("index.php?content=files_overview.php")
                }
                else {
                    returnElement.innerHTML = loginResponse.errorMessage;
                }
	        },
	        
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		alert(errorThrown);
	    	}
	    });
	});
});