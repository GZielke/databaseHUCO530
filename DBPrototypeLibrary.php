<?php
function insertNewUser($dbPipeline) {
	//query INSERT INTO user VALUES(NULL, "firstName", "lastName", "YYYY/MM/DD", "username", "password");
	$DOB = combineDate($_POST['user_input_year'],$_POST['user_input_month'],$_POST['user_input_day']);
	$userQuery = "INSERT INTO user VALUES(NULL, '{$_POST['user_input_firstName']}', '{$_POST['user_input_lastName']}', '$DOB', '{$_POST['user_input_username']}', '{$_POST['user_input_password']}')";
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

function insertNewEvent($dbPipeline){
	//query INSERT INTO event VALUES(NULL, "eventName", "eventCategory", "eventLocation", "points");
	$realPointInteger = intval($_POST['new_event_points']);
	$eventQuery = "INSERT INTO event VALUES(NULL, '{$_POST['new_event_eventName']}', '{$_POST['new_event_eventCategory']}', '{$_POST['new_event_eventLocation']}', '$realPointInteger')";
	mysqli_query($dbPipeline, $eventQuery);
	echo "New event created!";
};

function insertNewCompleteEvent($dbPipeline){
	$eventCompleteQuery = "INSERT INTO eventCompletion(username, eventName, dateComplete, journal) VALUES('{$_POST['ev_input_user']}', '{$_POST['ev_input_event']}', NOW(), '{$_POST['ev_input_journal']}')";
	mysqli_query($dbPipeline, $eventCompleteQuery);
	echo "Congratulations! You completed an event!";
};

function timeIn($dbPipeline){
    //When doing this, both of the timestamps need to be NULL.
    //i.e. INSERT INTO clock VALUES(NULL, *user id*, NULL, NULL)
    $timeInQuery = "INSERT INTO clock VALUES(NULL,'{$_POST['timeInId']}',NULL,NULL)";
    mysqli_query($dbPipeline,$timeInQuery);
	echo "You have checked in!";
};

function timeOut($dbPipeline){
    //When timing out, you use UPDATE clock SET timeOut = NOW() WHERE id = *person logging out*
    //We can find the "current" section by adding "WHERE timeIn = timeOut".
    $timeOutQuery = "UPDATE clock SET timeOut = NOW() WHERE username = '{$_POST['timeOutId']}' ORDER BY timeIn DESC LIMIT 1";
    mysqli_query($dbPipeline,$timeOutQuery);
	echo "You have checked out!";
};

function getEventHistory($dbPipeline){
	//Needs to look up all of the eventCompletion entries that correspond to the user.
	echo "getEventHistory() is being called.<br>";
	$eventHistoryQuery = "SELECT user.firstName, user.lastName, event.eventName, event.eventCategory, event.eventLocation, event.points, eventCompletion.dateComplete FROM user, eventCompletion, event WHERE user.id = '{$_POST['getEventHistoryId']}' AND user.id = eventCompletion.username AND eventCompletion.eventName = event.id";
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
		echo "<br>";
	};
};

?>