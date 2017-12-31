require(["lib/jquery.min"], function() {
	var returnElement = document.getElementById("php_return");
	
	$('#addURL').on('click', function() {
		var urlNameElem = document.getElementById("url_name");
		var elemName = $(urlNameElem).val();
		if (elemName === null || elemName === "") {
			alert("Please enter a name");
			return;
		}
		
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
	    		//alert("URL ADDED!");
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
	
	$('.deleteURL').on('click', function() {
		var url_id = $(this).attr("value");
		var nameElement = document.getElementById("name_"+url_id);
		var urlName = $(nameElement).attr("name");
		
		var confirmDelete = confirm("Remove URL " + urlName + "?");
		if (!confirmDelete) {
			return;
		}
		
		var form_Data = new FormData();
		form_Data.append("url_id", url_id);
		
	    $.ajax({
	        url: RP_PHP_DIR+'Scripts/delete_onetime_url.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	returnElement.innerHTML = php_script_response;
	    		//alert("URL REMOVED!");
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
	
	$('.renewURL').on('click', function() {	
		var url_id = $(this).attr("value");
		var nameElement = document.getElementById("name_"+url_id);
		var urlName = $(nameElement).attr("name");
	
		var confirmRenew = confirm("Renew URL "+urlName+"?");
		if (!confirmRenew) {
			return;
		}
		
		var form_Data = new FormData();
		form_Data.append("url_id", url_id);
		
	    $.ajax({
	        url: RP_PHP_DIR+'Scripts/renew_onetime_url.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(php_script_response) {
	        	returnElement.innerHTML = php_script_response;
	    		//alert("URL Renewed!");
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
	    		returnElement.innerHTML = jqXHR.responseText;
	    		alert(errorThrown);
	    	}
	    });
	});
});