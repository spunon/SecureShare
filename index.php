<?php
require('config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title><?php echo $org_name;?> SecureShare</title>
<link rel="stylesheet" href="style/swatchc.min.css" />
<link rel="stylesheet" href="style/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>

</head>

<body>
	<div style="width:500px; margin:0px auto;">
		<img src="logo.jpg"/>
		<p>Welcome to the <?php echo $org_name;?> SecureShare system. Use this system to upload files that are too large to be sent as email attachments. 
		Your file will be password protected, and the username/password will be automatically sent to the email addresses you specify below:</p>
		<form action="upload.php" method="post" enctype="multipart/form-data" data-ajax="false">
			Your email address:
			<input type="text" name="youremail" id="youremail" required/>
			Share it with another email address:
			<input type="text" name="shareemail" id="shareemail" required/>
			Select file to upload:
			<input type="file" name="uploadedfile" id="uploadedfile"/>
			<input type="submit" value="Upload File" name="submit"/>
			<p><i>If you're uploading a larger file please be patient. It could take a few seconds.</i></p>
		</form>
	</div>
</body>
</html>
