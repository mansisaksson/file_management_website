
$(':file').on('change', function() 
{
	var file = this.files[0];
    if (file.size < 1) {
        alert('this is not a valid file')
    }

    // Also see .name, .type
});

$(':button').on('click', function() 
{
	var file_data = $('#fileToUpload').prop('files')[0];   
	var form_data = new FormData();                  
	form_data.append('fileToUpload', file_data);
	
    $.ajax
    ({
        // Your server script to process the upload
        url: '/mansisaksson_webbpage/scripts/upload_file.php',
        type: 'POST',

        // Form data
        data: form_data,

        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
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
        
        success: function(php_script_response){
            //document.write(php_script_response); // display response from the PHP script, if any
            document.getElementById("uploadReturn").innerHTML = php_script_response;
        }
    });
});