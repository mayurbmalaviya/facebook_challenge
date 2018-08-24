Author: Suraj Singh
Author Email: spsrga176@gmail.com
Date : 14/04/2018

============ Introduction ============
This project helps web developers to implement the user registration with Google account using PHP at their website project.
Also the user information would be stored at the MySQL database.

============ Installation ============
1. Create a database (dbgmail) at phpMyAdmin.
2. Import the users.sql file into the database (dbgmail ).
4. Open the "config.php" file and modify the $clientId, $clientSecret, $redirectUrl  variables value with your Google 
	Project API credentials.
5. Test the functionalities.

============ May I Help You ===========
If you have any query about this script, please feel free to contact us at spsrga176@gmail.com (or 7284970941 ). We will reply your query soon.


==========Additional setting =========
Put the "cacert.pem" file in your wamp(or wamp64) folder
Add below two line in php.ini file
	curl.cainfo="C:/wamp64/cacert.pem"
	openssl.cafile="C:/wamp64/cacert.pem"
then restart the wamp server 



