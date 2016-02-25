<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title><?php echo $org_name;?> Secure Share</title>
<link rel="stylesheet" href="style/swatchc.min.css" />
<link rel="stylesheet" href="style/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.min.js"></script>

</head>

<body>
	<div style="width:500px; margin:0px auto;">
		<img src="logo.jpg" alt="Secure Share"/>
			
			
			<?php
			require('config.php');

			$youremail = $_POST['youremail'];
			$shareemail = $_POST['shareemail'];

			//generate random 8 digit hex value for directory name
			$dir = substr(md5(rand()), 0, 8);
			//generate random 10 digit hex value for password
			$pwd = substr(md5(rand()), 0, 10);
			//hash the password for use in the .htpasswd file
			$md5pwd = crypt($pwd, base64_encode($pwd));
			//set variable for the full path to the newly created directory
			$makepath = "$htmlpath/$uploadpath/$dir";
			//set variable for the full external url to the upload directory
			$url = "$org_uri/$uploadpath/$dir/";
			//make the new directory based on the random name, then chmod the 
			//directory to make it readable to everyone.
			mkdir($makepath,0775);
			chmod($makepath,0775);

			//define information for the .htaccess file in newly created directory
			//this will require that a username and password be given to access 
			//any file in the directory, and disallow download of the .htaccess file
			//and .htpasswd files from being downloaded.
			$htaccess = '
			#Deny access to htaccess
			<Files .htaccess>
			order allow,deny
			deny from all
			</Files>

			#Disable Directory Listing
			IndexIgnore *

			RewriteEngine On
			RewriteCond %{SERVER_PORT} 80
			RewriteCond %{REQUEST_URI} ' . $uploadpath . '/' . $dir . '
			RewriteRule ^(.*)$ ' . $url . '/$1 [R,L]

			AuthType Basic
			AuthName "Password Protected Area"
			AuthUserFile ' . $makepath . '/.htpasswd
			Require valid-user';

			//set paths for .htaccess and .htpasswd
			$htaccesspath = "$makepath/.htaccess";
			$htpasswdpath = "$makepath/.htpasswd";

			//write .htaccess and .htpassword to the newly created directory
			file_put_contents($htaccesspath, $htaccess);
			$passwd = $username . ':' . $md5pwd;
			file_put_contents($htpasswdpath, $passwd);

			//set data variable with current date for metadata.txt
			$date = date('l jS \of F Y h:i:s A');

			//build metadata string to dump into the metadata.txt file
			$metadata = 'Uploader Email: ' . $_POST['youremail'] . '
			Share Email: ' . $_POST['shareemail'] . '
			Date: '. $date . '
			';
			//save form data to metadata.txt in newly created directory, then chmod the 
			//file to make it readable to everyone.
			file_put_contents("$makepath/metadata.txt", $metadata);
			chmod($makepath . '/metadata.txt',0775);

			//create variables for target path, fullurl and filename based on the original file neme
			//then replace spaces with dashes because the internet be a bitch y'all
			$target_path = $makepath . '/' . basename( $_FILES['uploadedfile']['name']);
				$target_path = str_replace(' ', '-', $target_path);
			$fullurl = $url . basename( $_FILES['uploadedfile']['name']);
				$fullurl = str_replace(' ', '-', $fullurl);
			$filename = basename( $_FILES['uploadedfile']['name']);
				$filename = str_replace(' ', '-', $filename);

			//if the file uploaded and is moveable, move it out of /tmp and intothe directory we created for it
			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
				chmod($target_path,0775);
				
					//echo success message to be displayed on page and emailed.
					echo '<p>The file ' . $filename . ' has been uploaded and shared with you.</p>
					<div style="background-color:#eee; margin:10px; padding:10px 20px;">
					<p><strong>Shared File URL:</strong></p>
					<textarea>' . $fullurl . '</textarea>
					<p><strong>Username:</strong> ' . $username . '</p>
					<p><strong>Password:</strong> ' . $pwd . '</p>
					<p>An email with this information has been send to the following email addresses:</p>
					<ul>
						<li>' . $youremail . '</li>
						<li>' . $shareemail . '</li>
					</ul>
					</div>';
					
					//build email headers
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: ' . $youremail . "\r\n" .
						'Reply-To: ' . $youremail . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					
					$email_all = "$youremail, $shareemail";
					
					//build email message
					$emailmessage = '<html><body style="font-family:arial">
					<p>The file ' . $filename . ' has been uploaded and shared with you.</p>
					<table width="80%" style="font-family:arial">
						<tr><td width="20%"><strong>Shared File URL:</strong></td><td><a href="' . $fullurl . '">' . $fullurl . '</a></td></tr>
						<tr><td><strong>Username:</strong></td><td>' . $username . '</td></tr>
						<tr><td><strong>Password:</strong></td><td>' . $pwd . '</td></tr>
					</table>
					<p style="color:#ccc"><i>' . $privacy_statement . '</i></p>
					</body></html>';
					
					//send emails
					mail($email_all, "$org_name Secure Share Document", $emailmessage, $headers);
					
			} else{
				echo "<p>There was an error uploading the file, please press the back button and try again!</p>";
			}

			?>
	
	
		</div>
</body>
</html>




