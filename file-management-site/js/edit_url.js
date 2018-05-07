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
	        url: RP_PHP_SCRIPTS_DIR+'add_onetime_url.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(response) {
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
				let response = jqXHR.responseText;
	    		alert(response.message);
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
	        url: RP_PHP_SCRIPTS_DIR+'delete_onetime_url.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(response) {
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
				let response = jqXHR.responseText;
	    		alert(response.message);
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
	        url: RP_PHP_SCRIPTS_DIR+'renew_onetime_url.php',
	        type: 'POST',
	        data: form_Data,
	        cache: false,
	        contentType: false,
	        processData: false,
	        
	        success: function(response) {
	    		location.reload();
	        },
	    
	    	error: function(jqXHR, textStatus, errorThrown) {
				let response = jqXHR.responseText;
	    		alert(response.message);
	    	}
	    });
	});
});