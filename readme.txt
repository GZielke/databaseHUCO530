***********************************************/
* iHuman Moving the Mountain Database Project *
*											  *
* Created by Grady Zielke & Kara Au		      *
/**********************************************

As of April 16th, the final version of this iteration of the database
can be viewed at "http://hucodev.srv.ualberta.ca/kwau/iHuman/index.php"

THE ABOVE LINKED ITERATION OF THE DATABASE IS SUBJECT TO REMOVAL WITHOUT NOTICE!!
It is a test database and should NOT be used as the official iHuman database.

****************/
* INSTRUCTIONS *
/***************

// EDITING DBCONNECT.PHP TO CONNECT TO YOUR MYSQL DATABASE //
To do this, you require "dbconnect.php"

1. Open dbconnect.php in a simple text editor like Notepad. Do NOT use Microsoft Word.

2. All the values in CAPS must be edited with the correct database information. If this information is incorrect, you will have errors, and nothing will work:

HOST NAME, USERNAME, PASSWORD, DATABASE NAME

This is your MySQL database login information. Please consult whoever is in charge of your hosting for this information.

// UPLOADING FILES FOR FIRST TIME USE //
To do this, you require "index.php", "DBPrototype.php", "DBPrototypeLibrary.php", "dbconnect.php", "iHuman_database.sql", "stylesheet.css" and access to an FTP server.

1.	ALL THESE FILES MUST BE IN THE SAME FOLDER. THEY SHOULD BE IN THE FOLDER "MTM".
	Place the ENTIRE FOLDER on the FTP server. They should be on the FIRST LEVEL
	of the server and not housed within any other folders for proper redirection,
	or else you will have to edit link redirects within the php files manually.

	NOTE: This is for proper redirection purposes. The current path for redirection is
	set to "MTMdatabase/index.php" etc. Please have a professional present to alter any
	link redirects.

// SETTING UP THE DATABASE FOR FIRST TIME USE //
To do this, you require "iHuman_database.sql" and access to MySQL.

1.	If manually running, the query will be "SOURCE iHuman_database.sql;"
	WITH the semicolon in MySQL.

	NOTE: The admin account should be automatically created at that point, with the
	login information of "admin" (username) and "password" (password) and "1991-11-11"
	(date of birth). The password may be reset in the reset password function that
	is visible on the login page after setup (with username and date of birth.

// NAVIGATING TO THE WEBPAGE FOR FIRST TIME USE //

1.	Navigate to "yoursite.com/MTM/index.php" in your browser, where "yoursite.com" is the
	domain of your website. "index.php" is the login page of the database. You cannot
	access the other links until you have logged in. It will automatically redirect you
	to "index.php" if you navigate to "DBPrototype.php".

2.	Ensure that all functions are properly running. You should be able to log in with
	the admin account credentials provided above. The admin is capable of adding and
	editing point values and events. Regular users should not be able to see this.

*********/
* NOTES *
/********

1.	If the admin account creates an event, it can be accessed by all users.

2.	Only the admin can allocate point values to events. All other user-created events
	will have a point value of 0 until changed.
	
**********************/
* CODE DOCUMENTATION *
/*********************

The documentation for the code is all located within "DBPrototypeLibrary.php". You can
find the answers to how code works by opening that file in a text editor.