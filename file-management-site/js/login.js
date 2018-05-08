require(["lib/jquery.min"], function() {
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
				if (response.success) {
                	location.replace("index.php?content=file_overview.php")
				} else {
					alert(response.message);
				}
	        },
	        
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		alert(errorThrown);
	    	}
	    });
	});

	$('a[href="#logoutButton"]').on('click', function() {
        $.ajax({
	        url: RP_PHP_SCRIPTS_DIR+'logout.php',
	        type: 'POST',
	        data: null,
	        cache: false,
	        contentType: false,
	        processData: false,
            
	        success: function(response) {
				if (response.success) {
                	location.replace("index.php")
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