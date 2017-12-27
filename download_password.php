<?php
require_once 'header.php';
require_once FP_PHP_DIR.'Core/Globals.php';
?>

<body>
</body>

<script>
var password = prompt("Enter Password", "");

post('<?php echo RP_PHP_DIR."Scripts/download_file.php" ?>', {fileID: getUrlVars()["fileID"], password: password});

function post(path, params)
{
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.append(form);
    form.submit();
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}
</script>
