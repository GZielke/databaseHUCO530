<html>
<head>

<?php 
$dbPipeline = mysqli_connect('localhost', 'kwau', 'honied)effluent5pogroms_', 'kwaudb');
if(!$dbPipeline){
	die('Error: ' . mysqli_connect_errno() . '.');
};

include_once 'DBPrototypeLibrary.php';
?>
</head>

<body>
<?php
if(isset($_POST['insertingUser'])){
	insertNewUser($dbPipeline, $_POST['user_input_day'], $_POST['user_input_month'], $_POST['user_input_year'], $_POST['user_input_firstName'], $_POST['user_input_lastName'], $_POST['user_input_username'], $_POST['user_input_password']);
} elseif(isset($_POST['insertingEventComplete'])){
	insertNewCompleteEvent($dbPipeline, $_POST['ev_input_journal'], $_POST['ev_input_user'], $_POST['ev_input_event']);
} elseif(isset($_POST['insertingNewEvent'])){
	insertNewEvent($dbPipeline, $_POST['new_event_points'], $_POST['new_event_eventName'], $_POST['new_event_eventCategory'], $_POST['new_event_eventLocation']);
} elseif(isset($_POST['timeInComplete'])){
	 timeIn($dbPipeline, $_POST['timeInId']);
} elseif(isset($_POST['timeOutComplete'])){
	 timeOut($dbPipeline, $_POST['timeOutId']);
} elseif(isset($_POST['searchUserComplete'])){
	searchUser($dbPipeline, $_POST['searchUserId']);
} elseif(isset($_POST['getEventHistoryComplete'])){
	getEventHistory($dbPipeline, $_POST['getEventHistoryId']);
} elseif(isset($_POST['getPunchClockComplete'])){
	getPunchClock($dbPipeline, $_POST['getPunchClockId']);
};
?>

<form name='user_input' id='user_input' method='post'>
	<h2>Register New User</h2>
	First Name:
	<input type="text" name="user_input_firstName" id="user_input_firstName" value="" maxlength="30"><br><br>
	Last Name:
	<input type="text" name="user_input_lastName" id="user_input_lastName" value="" maxlength="30"><br><br>
	Date of Birth:
	Month:
	<select id='user_input_month' name='user_input_month'>
		<option value='01' selected>January</option>
		<option value='02' >February</option>
		<option value='03' >March</option>
		<option value='04' >April</option>
		<option value='05' >May</option>
		<option value='06' >June</option>
		<option value='07' >July</option>
		<option value='08' >August</option>
		<option value='09' >September</option>
		<option value='10' >October</option>
		<option value='11' >November</option>
		<option value='12' >December</option>
	</select>
	Day:
	<select id='user_input_day' name='user_input_day'>
		<?php
			print "<option value='01' selected>1</option>";
			for($i=2; $i<32; $i++){
				if($i<10){
					print "<option value='0$i'>$i</option>";
				}else{
					print "<option value='$i'>$i</option>";
				};
			};
		?>
	</select>
	Year:
	<select id='user_input_year' name='user_input_year'>
		<?php
			for($i=2015; $i>1900; $i--){
				print "<option value='$i'>$i</option>";
			};
		?>
	</select><br><br>
	Username:
	<input type="text" name="user_input_username" id="user_input_username" maxlength="30"><br><br>
	Password:
	<input type="text" name="user_input_password" id="user_input_password" maxlength="30"><br><br>
	<input type="hidden" name="insertingUser" id="insertingUser" value="true">
	<input type="submit" name="submit" id="submit" value="Submit">
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
	<textarea name='ev_input_journal' id='ev_input_journal' cols='80' rows='6' maxlength='999'></textarea>
	<input type="hidden" name="insertingEventComplete" id="insertingEventComplete" value="true"><br><br>
	<input type="submit" name="submit" id="submit" value="Submit">
</form>
<form name='new_event' id='new_event' method='post'>
	<h2>Create New Event:</h2>
	Event:
	<input type='text' name='new_event_eventName' id='new_event_eventName' maxlength='50'><br><br>
	Event Category:
	<input type='text' name='new_event_eventCategory' id='new_event_eventCategory' maxlength='30'><br><br>
	Event Location:
	<input type='text' name='new_event_eventLocation' id='new_event_eventLocation' maxlength='30'><br><br>
	Points:
	<input type='text' name='new_event_points' id='new_event_points'>
	<input type='hidden' name='insertingNewEvent' id='insertingNewEvent' value="true"><br><br>
	<input type="submit" name="submit" id="submit" value="Submit">
</form>

<form name = "timeIn" id = "timeIn" method = "post">
    <h2>Time In:</h2>
    User:
    <select id = "timeInId" name = "timeInId">
    <?php
   	 wrapInOptionsTags(listAllUsers($dbPipeline));
    ?>
    </select><br><br>
    <input type = "hidden" name = "timeInComplete" id = "timeInComplete" value = "true">
    <input type = "submit" name = "submit" id = "submit" value = "Sigh In">
</form>
<form name = "timeOut" id = "timeOut" method = "post">
    <h2>Time Out:</h2>
    User:
    <select id = "timeOutId" name = "timeOutId">
    <?php
   	 wrapInOptionsTags(listAllUsers($dbPipeline));
    ?>
    </select><br><br>
    <input type = "hidden" name = "timeOutComplete" id = "timeOutComplete" value = "true">
    <input type = "submit" name = "submit" id = "submit" value = "Sigh Out">
</form>

<form name = "searchUser" id = "searchUser" method = "post">
	<h2>Search User and Get Total Points:</h2>
	<select id = "searchUserId" name = "searchUserId">
		<?php
			wrapInOptionsTags(listAllUsers($dbPipeline));
		?>
	</select><br><br>
	<input type = "hidden" name = "searchUserComplete" id = "searchUserComplete" value = "true">
	<input type='submit' name='submit' id='submit' value='Search'>
</form>

<form name = "getEventHistory" id = "getEventHistory" method = "post">
	<h2>Get Event History for User:</h2>
	<select id = "getEventHistoryId" name = "getEventHistoryId">
		<?php
			wrapInOptionsTags(listAllUsers($dbPipeline));
		?>
	</select><br><br>
	<input type = "hidden" name = "getEventHistoryComplete" id = "getEventHistoryComplete" value = "true">
	<input type = "submit" name = "submit" id = "submit" value = "View History">
</form>

<form name="getPunchClock" id="getPunchClock" method="post">
	<h2>Get Punch Clock History for User:</h2>
	<select id = "getPunchClockId" name = "getPunchClockId">
		<?php
			wrapInOptionsTags(listAllUsers($dbPipeline));
		?>
	</select><br><br>
	<input type = "hidden" name = "getPunchClockComplete" id="getPunchClockComplete" value = "true">
	<input type = "submit" name = "submit" id = "submit" value = "View Punch Clock">
</form>

</body>

</html>
