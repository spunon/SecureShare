SecureShare
===========
1.) This system utilizes apache web server's .htaccess and .htpasswd systems as well as mod_rewrite, so you'll need a server running apache web server for this program to function correctly.

2.) This system relies on a working SSL certificate being installed and configured for your site. All generated URIs are HTTPS. All uploaded folders are mod_rewritten to HTTPS by design.

3.) After extracting this zip file and upload the 'secureshare' directory into the root of your web servers html folder. (e.g. /var/www/html/secureshare)

4.) Edit config.php and put in your own information for the variables. You'll need to know your apache html folder from the last step.

5.) Rename logo.jpg and upload your own logo that is 500px wide.

Release Notes:
2/8/16 - Later releases will have user definable security around where uploads can originate from, a configuration UI, more metadata, as well as an auto-pruning function that kills off old files as new files are added.