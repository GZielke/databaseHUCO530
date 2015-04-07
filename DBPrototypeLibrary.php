<?php
function insertNewUser($dbPipeline, $year, $month, $day, $firstname, $lastname, $username, $password) {
	//This function TAKES the database connection, a year of birth, a month of birth, a day of birth, a first name, a last name, a username, and a password
	//and RETURNS nothing.
	//This function updates the database with a new user complete with birth date, first and last name, username, and password.
	//Calling the function will look like this:
	//insertNewUser($databaseConnection, $yearThey'reBorn, $MonthThey'reBorn, $dayThey'reBorn, $firstName, $lastName, $username, $password);
	//This doesn't log you in, it creates a new set of log in information.
	//This also checks to see if there is already someone with the selected username before adding that username to the database. It doesn't allow for
	//multiple instances of the same username, but does allow multiples of other features.
	//Below are our notes for interacting with the database:
		//query INSERT INTO user VALUES(NULL, "firstName", "lastName", "dateOfBirth(in format YYYY/MM/DD)", "username", "password");
	$DOB = combineDate($year, $month, $day);
	$userCheckQuery = "SELECT * FROM user WHERE username = '$username'";
	$usernameCheck = mysqli_num_rows(mysqli_query($dbPipeline, $userCheckQuery));
	$username = addslashes($username);
	$firstname = addslashes($firstname);
	$lastname = addslashes($lastname);
	$password = addslashes($password);
	if($usernameCheck == 0){
		$userInsertQuery = "INSERT INTO user VALUES(NULL, '$firstname', '$lastname', '$DOB', '$username', '$password')";
		mysqli_query($dbPipeline, $userInsertQuery);
		echo "The user " . stripslashes($username) . " is now registered!";
	} else {
		echo "Username is already in use. Please choose another username.";
	};
};

function combineDate($year, $month, $day){
	//This function just takes the information from the insertNewUser function and puts it in a format the database likes.
	return "$year"."-"."$month"."-"."$day";
};

function listAllUsers($dbPipeline) {
	//This function TAKES the database connection and RETURNS a full list of all the usernames in the database.
	//This function doesn't update the database with new information.
	//Calling the function will look like this:
	//$usernameArray = listAllUsers();
	//The array that is returned, $resultsArray, will have:
	//$resultsArray['0'] is the first username. Each username is connected to their id value.
	$userQuery = "SELECT username, id FROM user WHERE id > 1";
	$userCloud = mysqli_query($dbPipeline, $userQuery);
	$resultArray = array();
	while($userData = mysqli_fetch_assoc($userCloud)){
		$resultArray[$userData['id']] = $userData['username'];
	};
	return $resultArray;
};

function listAllEvents($dbPipeline) {
	//This function TAKES the database connection and RETURNS a full list of all the events in the database.
	//This function doesn't update the database with new information.
	//Calling the function will look like this:
	//$EventNameArray = listAllEvents($databaseConnection);
	//The array that is returned, $resultsArray, will have:
	//$resultsArray['0'] is the first event name. Each event name is connected to their id value.
	$eventCloud = mysqli_query($dbPipeline, "SELECT event.eventName, event.id, user.username FROM event, user WHERE event.username = user.id");
	$resultArray = array();
	while($eventData = mysqli_fetch_assoc($eventCloud)){
		$labelledInfo = $eventData['eventName'] . ' - '. $eventData['username'];
		$resultArray[$eventData['id']] = $labelledInfo;
	};
	return $resultArray;
};

function listCurrentUserEvents($dbPipeline, $userId){
	//This function TAKES the database connection and a user id and RETURNS a list of events specific to that user and the admin events.
	//This function doesn't update the database with new information,
	//Call the function will look like this:
	//$EventNameArray = listCurrentUserEvents($databaseConnection, $userId);
	//The array that is returned, $results array, will have:
	//$resultsArray['*someNumber*'] is an event name. Each event is tied to its id. There's no guarentee that the array starts at one because this function
	//ONLY returns events created by the user that is logged in. It also returns events craeted by the admin, which allows the database to have a "default"
	//set of events.
	$eventCloud = mysqli_query($dbPipeline, "SELECT eventName, id FROM event WHERE username = '$userId' OR username = 1");
	$resultArray = array();
	while($eventData = mysqli_fetch_assoc($eventCloud)){
		$resultArray[$eventData['id']] = $eventData['eventName'];
	};
	return $resultArray;
};

function wrapInOptionsTags($optionArray) {
	//This function just wraps things in option tags. It's only really useful in HTML.
	//If you combine it with the listAllEvents() or listAllUsers() functions, if creates a selectable list within a form.
	//The function looks like this: wrapInOptionsTags(listAllUsers());
	foreach($optionArray as $key => $value){
		print "<option value='$key'>$value</option>";
	};
};

function insertNewEvent($dbPipeline, $userId, $eventName, $eventCategory, $eventLocation, $points){
	//This function TAKES the database connection, the point value for a new event, the name of a new event, the category for a new event,
	//the location for a new event, and a user's id and RETURNS nothing.
	//This function updates the database with a new event. You can determine the name of the event, its category, location, point value, and the user who created it.
	//Calling the function will look like this:
	//insertNewEvent($databaseConnection, $pointValueOfEvent, $nameOfEvent, $categoryOfEvent, $locationOfEvent, $userId);
	//A note: if the admin creates a function, it is accessible to all users when using the listCurrentUserEvents() function. 
	//Below are our notes for interacting with the database:
		//query INSERT INTO event VALUES(NULL, "eventName", "eventCategory", "eventLocation", "points");
	$realPointInteger = intval($points);
	$eventName = addslashes($eventName);
	$eventLocation = addslashes($eventLocation);
	$eventQuery = "INSERT INTO event VALUES(NULL, '$userId', '$eventName', '$eventCategory', '$eventLocation', '$realPointInteger')";
	mysqli_query($dbPipeline, $eventQuery);
	echo "New event created!\n";
	echo "The point value of the event is 0 until an admin changes its value.";
};

function insertNewCompleteEvent($dbPipeline, $journal, $user, $event){
	//This function TAKES the database connection, a journal entry, a user id, and an event id and RETURNS nothing.
	//This function updates the database with a new completed event by a user. You can access that information with other functions.
	//Calling the function will look like this:
	// insertNewCompleteEvent($databaseConnection, $journalEntry, $IdOfPersonWhoCompletedAnEvent, $IdOfEventThePersonCompleted);
	$formattedJournal = nl2br(addslashes($journal));
	$eventCompleteQuery = "INSERT INTO eventCompletion(username, eventName, dateComplete, journal) VALUES('$user', '$event', NOW(), '$formattedJournal')";
	mysqli_query($dbPipeline, $eventCompleteQuery);
	echo "Congratulations! You completed an event!";
};

function insertMood($dbPipeline,$user,$mood,$journal){
	$formattedJournal = nl2br(addslashes($journal));
	$query = "INSERT INTO mtmRegistry(username,dateComplete,mood,journal) VALUES('$user',CURDATE(),'$mood','$formattedJournal')";
	mysqli_query($dbPipeline,$query);
	echo "Current mood submitted.";
}

function timeIn($dbPipeline, $timeInId){
	//This function TAKES the database connection and a user id and RETURNS nothing.
	//This function updates the database with the time that the person whose id you enter checks in.
	//Calling the function will look like this:
	//timeIn($databaseConnection, $IdOfPersonYouWantToCheckIn);
	//Below are our notes for interacting with the database:
		//When doing this, both of the timestamps need to be NULL.
		//i.e. INSERT INTO clock VALUES(NULL, *user id*, NULL, NULL)
    $timeInQuery = "INSERT INTO clock VALUES(NULL,'$timeInId',NULL,NULL)";
    mysqli_query($dbPipeline,$timeInQuery);
};

function timeOut($dbPipeline, $timeOutId){
	//This function TAKES the database connection and a user id and RETURNS nothing.
	//This function updates the database with the time that the person whose id you enter checks out.
	//Calling the function will look like this:
	//timeOut($databaseConnection, $IdOfPersonYouWantToCheckOut);
	//Below are our notes for interacting with the database:
		//When timing out, you use UPDATE clock SET timeOut = NOW() WHERE id = *person logging out*
		//We can find the "current" section by adding "WHERE timeIn = timeOut".
    $timeOutQuery = "UPDATE clock SET timeOut = NOW() WHERE username = '$timeOutId' ORDER BY timeIn DESC LIMIT 1";
    mysqli_query($dbPipeline,$timeOutQuery);
};

function getEventHistory($dbPipeline, $getEventHistoryId){
	//This function TAKES the database connection and a user id and RETURNS	all of the events that a user has completed as arrays within an array.
	//This function doesn't update the database with new information.
	//Calling the function would look like: 
	//$myArray = getEventHistory($databaseConnection, $idOfPersonYou'reLookingFor);
	//The array that is returned, $historyFinalArray, will have $historyFinalArray['firstName'] is the user's first name, 
	//$historyFinalArray['lastName'] is the user's last name, $historyFinalArray['eventName'] is the name of the event completed, 
	//$historyFinalArray['eventCategory'] is the name of the category of that event,
	//$historyFinalArray['eventLocation'] is where the event was completed (Library, pool, etc.) $historyFinalArray['points'] is how many points the event was worth,
	//$historyFinalArray['dateComplete'] is the date that the event was completed, and $historyFinalArray['journal'] is the journal entry corresponding to that event.
	//Each complete event is an array within the array. So, $historyFinalArray[0][eventName] gets the name of the first event, historyFinalArray[1][eventName] gets the name of the second event, etc.
	//This gets EVERYTHING that a user has done within the database. If you just want the user's point total, using searchUser() would be faster.
	echo "getEventHistory() is being called.<br>";
	$historyFinalArray = array();
	$eventHistoryQuery = "SELECT user.firstName, user.lastName, event.eventName, event.eventCategory, event.eventLocation, event.points, eventCompletion.dateComplete, eventCompletion.journal FROM user, eventCompletion, event WHERE user.id = '$getEventHistoryId' AND user.id = eventCompletion.username AND eventCompletion.eventName = event.id";
	$historyCloud = mysqli_query($dbPipeline, $eventHistoryQuery);
	while($historyData = mysqli_fetch_assoc($historyCloud)){
		$historyFinalArray[] = $historyData;
		print_r($historyData);
		echo "<br>";
	};
	return $historyFinalArray;
};

function searchUser($dbPipeline, $searchUserId){
	//This function TAKES the database connection and a user id and RETURNS	that user's first name, last name, and the total points they have right now.
	//This function doesn't update the database with new information.
	//Calling the function will look like this: 
	//$myArray = searchUser($databaseConnection, $idOfPersonYou'reLookingFor);
	//The array that is returned, $finalSearchUserArray, will have:
	//$finalSearchUserArray['firstName'] for the first name, $finalSearchUserArray['lastName'] for the last name,
	//and $finalSearchUserArray['totalPoints'] for the total points.
	//This function only returns one array, not an array of arrays. So accessing $myArray['firstName'] will return someone's first name.
	//This function is the easiest way to get total points.
	echo "searchUser() is being called.<br>";
	$finalSearchUserArray = array();
	$totalPoints = 0;
	$firstName = "";
	$lastName = "";
	$pointReturnQuery = "SELECT user.firstName, user.lastName, event.points FROM user, eventCompletion, event WHERE user.id = '$searchUserId' AND user.id = eventCompletion.username AND eventCompletion.eventName = event.id";
	$pointReturnCloud = mysqli_query($dbPipeline, $pointReturnQuery);
	while($pointReturnData = mysqli_fetch_array($pointReturnCloud)){
		$totalPoints = $totalPoints + intval($pointReturnData['points']);
		$firstName = $pointReturnData['firstName'];
		$lastName = $pointReturnData['lastName'];
	};
	echo "\n" . $lastName;
	echo ", ";
	echo $firstName;
	echo " | ";
	echo $totalPoints;
	echo " total points.";
	$finalSearchUserArray['firstName'] = $firstName;
	$finalSearchUserArray['lastName'] = $lastName;
	$finalSearchUserArray['totalPoints'] = $totalPoints;
	return $finalSearchUserArray;
};

function getPunchClock($dbPipeline, $getPunchClockId){
	//This function TAKES the database connection and a user id and RETURNS the list of punch ins and punch outs of that user in an array of arrays.
	//This function doesn't update the database with new information.
	//Calling the function will look like:
	//$punchClockArray = getPunchClock($databaseConnection, $idOfPersonYouWantThePunchClockInformationFor);
	//The array that is returned, $clockArray, will have:
	//$clockArray[timeIn] is "what time they checked in," $clockArray[timeOut] is "what time they checked out."
	//Each of these arrays are contained within a larger array. So $clockArray[0][timeIn] is the first "timeIn", $clockArray[1][timeIn] is the second, and so on.
	$clockArray = array();
	$query = "SELECT timeIn,timeOut FROM clock WHERE username = '$getPunchClockId'";
	$clockCloud = mysqli_query($dbPipeline,$query);
	while($clock = mysqli_fetch_assoc($clockCloud)){
		print_r($clock);
		echo '<br>';
		$clockArray[] = $clock;
	};
	return $clockArray;
};

function findUserId ($dbPipeline, $username) {
	//This function TAKES the database connection and a username and RETURNS the id of that user.
	//This function doesn't update the database with new information.
	//Calling the function will look like this:
	//$userIdIWantToFind = findUserId($databaseConnection, $usernameOfPersonIWantTheIdFor);
	//This function returns the user id as a string.
	//You can call this function if you have a username but need the id for another function.
	$finalUserId = "Error: No user of that name found.";
	$query = "SELECT id FROM user WHERE username = '$username'";
	$userCloud = mysqli_query($dbPipeline, $query);
	$userIdData = mysqli_fetch_assoc($userCloud);
	$finalUserId = $userIdData['id'];
	return $finalUserId;
};

function findEventId ($dbPipeline, $eventName) {
	//This function TAKES the database connection and an event name and RETURNS the id of that event.
	//This function doesn't update the database with new information.
	//Calling the function will look like this:
	//$eventIdIWantToFind = findEventId($databaseConnection, $eventNameIWantTheIdFor);
	//This function returns the user id as a string.
	//You can call this function if you have an event name but need the id for another function.
	$finalEventId = "Error: No event of that name found.";
	$query = "SELECT id FROM event WHERE eventName = '$eventName'";
	$eventCloud = mysqli_query($dbPipeline, $query);
	$eventIdData = mysqli_fetch_assoc($eventCloud);
	$finalEventId = $eventIdData['id'];
	return $finalEventId;
};

function login ($dbPipeline, $username, $password){
	//This function TAKES the database connection, a username, and a password and RETURNS whether or not those are valid credentials to allow you to log in.
	//This function doesn't update the database with new information.
	//Calling this function will look like this:
	//if(login($databaseConnection, $myUsername, $myPassword)){
	//	Do the thing you want when the person logs in.
	//};
	//This function returns a true or false value. Basically, it answers the question, "Does someone with these credentials exists on the database?"
	$username = addslashes($username);
	$password = addslashes($password);
	$query = "SELECT * FROM user WHERE password = '$password' AND username = '$username'";
	$userCloud = mysqli_query($dbPipeline,$query);
	$passwordCheck = mysqli_num_rows($userCloud);
	if($passwordCheck == 1){
		session_start();
		while($cloud = mysqli_fetch_assoc($userCloud)){
			$_SESSION['loggedin'] = true;
			$_SESSION['firstName'] = $cloud['firstName'];
			$_SESSION['username'] = $cloud['username'];
			$_SESSION['id'] = $cloud['id'];
			echo "Welcome, " . $_SESSION['firstName'] . "! <a href=/kwau/iHuman/DBPrototype.php>Continue</a>";
		}
		timeIn($dbPipeline,$_SESSION['id']);
	}
	else{
		echo "Your login credentials are incorrect.";
	};
};

function logout($dbPipeline){
	timeOut($dbPipeline,$_SESSION['id']);
	session_destroy();
	header("Location: /kwau/iHuman/index.php");
	exit();
}

function resetPassword ($dbPipeline, $username, $day, $month, $year, $newPassword){
	//This function TAKES the database connection, username of the user that forgot their password, day, month, and year of that person's date of birth,
	//and the new password they want to input and RETURNS nothing.
	//This function updates the database with a new password for one particular user.
	$DOB = combineDate($year, $month, $day);
	$newPassword = addslashes($newPassword);
	$username = addslashes($username);
	$query = "UPDATE user SET password = '$newPassword' WHERE username = '$username' AND dateOfBirth = '$DOB'";
	if(mysqli_query($dbPipeline, $query)){
		echo "Password successfully changed.";
	}
	else{
		echo "Password not changed. Input not correct.";
	}
};

function updateEventPoints ($dbPipeline, $eventId, $newPoints) {
	//This function TAKES the database connection, the id of an event, and the new point value that you want to assign to that event and RETURNS nothing.
	//This function updates the database with a new value for an event.
	//Calling the function will look like this:
	//updateEventPoints($databaseConnection, $eventId of event you want to change, $new point value you want to assign to that event);
	//This is a function that allows administrators to change the point value of an event. This will automatically update all of the points that
	//everyone in the database earned from that event.
	$newPointsInt = intval($newPoints);
	$query = "UPDATE event SET points = '$newPointsInt' WHERE id = '$eventId'";
	mysqli_query($dbPipeline, $query);
	echo "Point value successfully updated!";
};

function getMood ($dbPipeline, $userId){
	//This function TAKES the database connection and the id of a user and returns the history of that user's mood ratings.
	//This function doesn't update the database.
	//Calling this function will look like this:
	//$variable = getMood($databaseConnection, $IdOfUserYouAreLookingFor);
	//The array that is returned, $moodArray, will have:
	//$moodArray[firstName] is the first name of the user you're looking for, $moodArray[lastName] is the last name of the user you're looking for, 
	//$moodArray[mood] is their mood for that instance,
	//$moodArray[dateComplete] is the date of that mood, and $moodArray[journal] is the journal entry for that instance.
	//Each of these arrays are contained within a larger array. So $moodArray[0][mood] is the first "mood", $moodArray[1][mood] is the second, and so on.
	echo "getMood() is being called.<br>";
	$moodArray = array();
	$moodData = '';
	$query = "SELECT user.firstName, user.lastName, mtmRegistry.mood, mtmRegistry.dateComplete, mtmRegistry.journal FROM user, mtmRegistry WHERE user.id = '$userId' AND user.id = mtmRegistry.username";
	$moodCloud = mysqli_query($dbPipeline, $query);
	while($moodData = mysqli_fetch_assoc($moodCloud)){
		$moodArray[] = $moodData;
		print_r($moodData);
		echo "<br>";
	};
	return $moodArray;
};
?>