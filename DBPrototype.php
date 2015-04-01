<html>
<head>

<?php 
$dbPipeline = mysqli_connect('localhost', 'kwau', 'honied)effluent5pogroms_', 'kwaudb');
if(!$dbPipeline){
	die('Error: ' . mysqli_connect_errno() . '.');
};

include_once 'DBPrototypeLibrary.php';
session_start();
?>
</head>

<body>
<?php
if($_SESSION['loggedin'] == true){
	echo "You are logged in as " . $_SESSION['firstName'] . ".\n";
}
else{
	header("Location: /kwau/iHuman/index.php");
	exit();
}
?>
<?php
if(isset($_POST['recordEventSubmit'])){
	insertNewCompleteEvent($dbPipeline, $_POST['ev_input_journal'], $_POST['ev_input_user'], $_POST['ev_input_event']);
}
if(isset($_POST['newEventSubmit'])){
	insertNewEvent($dbPipeline, $_POST['new_event_points'], $_POST['new_event_eventName'], $_POST['new_event_eventCategory'], $_POST['new_event_eventLocation']);
}
if(isset($_POST['searchUserSubmit'])){
	searchUser($dbPipeline, $_POST['searchUserId']);
}
if(isset($_POST['eventHistorySubmit'])){
	getEventHistory($dbPipeline, $_POST['getEventHistoryId']);
}
if(isset($_POST['punchClockSubmit'])){
	getPunchClock($dbPipeline, $_POST['getPunchClockId']);
}
if(isset($_POST['logout'])){
	logout($dbPipeline);
}
if(isset($_POST['moodSubmit'])){
	insertMood($dbPipeline,$_SESSION['id'],$_POST['mood'],$_POST['moodJournal']);
}
?>

<form name='logoutForm' id='logoutForm' method='post'>
<input type='submit' name='logout' id='logout' value='Log Out'>
</form>

<h2>Current Mood</h2>

<form name='moodForm' id='moodForm' method='post'>
From 1 (Happy) to 5 (Sad), how do you feel today?
<select name='mood' id='mood'>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
<option value='5'>5</option>
</select> <br><br>
<textarea name='moodJournal' id='moodJournal' cols='80' rows='6'></textarea><br><br>
<input type='submit' id='moodSubmit' name='moodSubmit' value="Submit">
</form>

<form name='ev_input' id='ev_input' method='post'>
	<h2>Record a Completed Event:</h2>
	Event:
	<select id='ev_input_event' name='ev_input_event'>
		<?php
			wrapInOptionsTags(listAllEvents($dbPipeline));
		?>
	</select><br><br>
	User:
	<select id="ev_input_user" name="ev_input_user">
		<?php
			wrapInOptionstags(listAllUsers($dbPipeline));
		?>
	</select><br><br>
	<textarea name='ev_input_journal' id='ev_input_journal' cols='80' rows='6'></textarea>
	<input type="submit" name="recordEventSubmit" id="recordEventSubmit" value="Record Event">
</form>

<form name='new_event' id='new_event' method='post'>
	<h2>Create New Event:</h2>
	Event:
	<input type='text' name='new_event_eventName' id='new_event_eventName' maxlength='50'><br><br>
	Event Category:
	<select name='new_event_eventCategory' id='new_event_eventCategory'>
	<option value='learningEducation'>Learning and Education</option>
	<option value='healthcare'>Healthcare</option>
	<option value='selfDevelopment'>Self Development</option>
	</select><br><br>
	Event Location:
	<input type='text' name='new_event_eventLocation' id='new_event_eventLocation' maxlength='30'><br><br>
	Points:
	<input type='text' name='new_event_points' id='new_event_points'>
	<input type="submit" name="newEventSubmit" id="newEventSubmit" value="Create Event">
</form>

<form name = "searchUser" id = "searchUser" method = "post">
	<h2>Search User and Get Total Points:</h2>
	<select id = "searchUserId" name = "searchUserId">
		<?php
			wrapInOptionsTags(listAllUsers($dbPipeline));
		?>
	</select><br><br>
	<input type='submit' name='searchUserSubmit' id='searchUserSubmit' value='Search'>
</form>

<form name = "getEventHistory" id = "getEventHistory" method = "post">
	<h2>Get Event History for User:</h2>
	<select id = "getEventHistoryId" name = "getEventHistoryId">
		<?php
			wrapInOptionsTags(listAllUsers($dbPipeline));
		?>
	</select><br><br>
	<input type = "submit" name = "eventHistorySubmit" id = "eventHistorySubmit" value = "View History">
</form>

<form name="getPunchClock" id="getPunchClock" method="post">
	<h2>Get Punch Clock History for User:</h2>
	<select id = "getPunchClockId" name = "getPunchClockId">
		<?php
			wrapInOptionsTags(listAllUsers($dbPipeline));
		?>
	</select><br><br>
	<input type = "submit" name = "punchClockSubmit" id = "punchClockSubmit" value = "View Punch Clock">
</form>

</body>

</html>
