<?php
function insertNewUser($dbPipeline, $year, $month, $day, $firstname, $lastname, $username, $password) {
	//query INSERT INTO user VALUES(NULL, "firstName", "lastName", "YYYY/MM/DD", "username", "password");
	$password = crypt($password);
	$DOB = combineDate($year, $month, $day);
	$userQuery = "INSERT INTO user VALUES(NULL, '$firstname', '$lastname', '$DOB', '$username', '$password')";
	mysqli_query($dbPipeline, $userQuery);
	echo "Inserted new user into the database!";
};

function combineDate($year, $month, $day){
	return "$year"."-"."$month"."-"."$day";
};

function listAllUsers($dbPipeline) {
	$userQuery = "SELECT username, id FROM user";
	$userCloud = mysqli_query($dbPipeline, $userQuery);
	$resultArray = array();
	while($userData = mysqli_fetch_assoc($userCloud)){
		$resultArray[$userData['id']] = $userData['username'];
	};
	return $resultArray;
};

function listAllEvents($dbPipeline) {
	$eventCloud = mysqli_query($dbPipeline, "SELECT eventName, id FROM event");
	$resultArray = array();
	while($eventData = mysqli_fetch_assoc($eventCloud)){
		$resultArray[$eventData['id']] = $eventData['eventName'];
	};
	return $resultArray;
};

function wrapInOptionsTags($optionArray) {
	foreach($optionArray as $key => $value){
		print "<option value='$key'>$value</option>";
	};
};

function insertNewEvent($dbPipeline, $points, $eventName, $eventCategory, $eventLocation){
	//query INSERT INTO event VALUES(NULL, "eventName", "eventCategory", "eventLocation", "points");
	$realPointInteger = intval($points);
	$eventQuery = "INSERT INTO event VALUES(NULL, '$eventName', '$eventCategory', '$eventLocation', '$realPointInteger')";
	mysqli_query($dbPipeline, $eventQuery);
	echo "New event created!";
};

function insertNewCompleteEvent($dbPipeline, $journal, $user, $event){
	$formattedJournal = addslashes($journal);
	$eventCompleteQuery = "INSERT INTO eventCompletion(username, eventName, dateComplete, journal) VALUES('$user', '$event', NOW(), '$formattedJournal')";
	mysqli_query($dbPipeline, $eventCompleteQuery);
	echo "Congratulations! You completed an event!";
};

function timeIn($dbPipeline, $timeInId){
    //When doing this, both of the timestamps need to be NULL.
    //i.e. INSERT INTO clock VALUES(NULL, *user id*, NULL, NULL)
    $timeInQuery = "INSERT INTO clock VALUES(NULL,'$timeInId',NULL,NULL)";
    mysqli_query($dbPipeline,$timeInQuery);
	echo "You have checked in!";
};

function timeOut($dbPipeline, $timeOutId){
    //When timing out, you use UPDATE clock SET timeOut = NOW() WHERE id = *person logging out*
    //We can find the "current" section by adding "WHERE timeIn = timeOut".
    $timeOutQuery = "UPDATE clock SET timeOut = NOW() WHERE username = '$timeOutId' ORDER BY timeIn DESC LIMIT 1";
    mysqli_query($dbPipeline,$timeOutQuery);
	echo "You have checked out!";
};

function getEventHistory($dbPipeline, $getEventHistoryId){
	//Needs to look up all of the eventCompletion entries that correspond to the user.
	echo "getEventHistory() is being called.<br>";
	$eventHistoryQuery = "SELECT user.firstName, user.lastName, event.eventName, event.eventCategory, event.eventLocation, event.points, eventCompletion.dateComplete, eventCompletion.journal FROM user, eventCompletion, event WHERE user.id = '$getEventHistoryId' AND user.id = eventCompletion.username AND eventCompletion.eventName = event.id";
	$historyCloud = mysqli_query($dbPipeline, $eventHistoryQuery);
	while($historyData = mysqli_fetch_assoc($historyCloud)){
		echo $historyData['lastName'];
		echo ", ";
		echo $historyData['firstName'];
		echo " | ";
		echo $historyData['eventName'];
		echo " | ";
		echo $historyData['eventCategory'];
		echo " | ";
		echo $historyData['eventLocation'];
		echo " | ";
		echo $historyData['points'];
		echo " | ";
		echo $historyData['dateComplete'];
		echo " | ";
		echo $historyData['journal'];
		echo "<br>";
	};
};

function searchUser($dbPipeline, $searchUserId){
	echo "searchUser() is being called.<br>";
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
	echo $lastName;
	echo ", ";
	echo $firstName;
	echo " | ";
	echo $totalPoints;
	echo " total points.";
};

function getPunchClock($dbPipeline,$getPunchClockId){
	$query = "SELECT timeIn,timeOut FROM clock WHERE username = '$getPunchClockId'";
	$clockCloud = mysqli_query($dbPipeline,$query);
	while($clock = mysqli_fetch_assoc($clockCloud)){
		print_r($clock);
		echo '<br>';
	};
};
?>