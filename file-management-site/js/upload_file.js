require(["lib/jquery.min"], function() {
	var returnElement = document.getElementById("php_return");
	
	$(':file').on('change', function() {
		var file = this.files[0];
	    if (file.size < 1) {
	        alert('this is not a valid file')
	    }
	
	    // Also see .name, .type
	});
	
	$('#uploadButton').on('click', function() {
		var formElement = document.forms.namedItem("fileForm");
		var form_Data = new FormData(formElement);
		
	    $.ajax({
	        url: RP_PHP_SCRIPTS_DIR+'upload_file.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	
	        // Custom XMLHttpRequest
	        xhr: function() {
	            var myXhr = $.ajaxSettings.xhr();
	            if (myXhr.upload) 
	            {
	                // For handling the progress of the upload
	                myXhr.upload.addEventListener('progress', function(e) {
	                    if (e.lengthComputable) {
	                        $('progress').attr({
	                            value: e.loaded,
	                            max: e.total,
	                        });
	                    }
	                } , false);
	            }
	            return myXhr;
	        },
	        
	        success: function(php_script_response) {
	        	returnElement.innerHTML = php_script_response;
	            alert("File uploaded successfully");
	            location.reload();
	        },
	        
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
});