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
	insertNewUser($dbPipeline);
} elseif(isset($_POST['insertingEventComplete'])){
	insertNewCompleteEvent($dbPipeline, $_POST['journal']);
} elseif(isset($_POST['insertingNewEvent'])){
	insertNewEvent($dbPipeline);
} elseif(isset($_POST['timeInComplete'])){
    timeIn($dbPipeline);
} elseif(isset($_POST['timeOutComplete'])){
    timeOut($dbPipeline);
};
?>

<form name='search' id='search' method='post'>
	<h2>Search for user:</h2>
	<input type="text" name='user_search' id='user_search'><br>
	<input type="submit" name="submit" id="submit" value="Submit">
</form>
<form name='user_input' id='user_input' method='post'>
	<h2>Input New User</h2><br>
	First Name:
	<input type="text" name="user_input_firstName" id="user_input_firstName" value="" maxlength="30">
	Last Name:
	<input type="text" name="user_input_lastName" id="user_input_lastName" value="" maxlength="30"><br>
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
	</select><br>
	Username:
	<input type="text" name="user_input_username" id="user_input_username" maxlength="30">
	Password:
	<input type="text" name="user_input_password" id="user_input_password" maxlength="30"><br>
	<input type="hidden" name="insertingUser" id="insertingUser" value="true">
	<input type="submit" name="submit" id="submit" value="Submit">
</form>
<form name='ev_input' id='ev_input' method='post'>
	Add new event!
	Event:
	<select id='ev_input_event' name='ev_input_event'>
		<?php
			wrapInOptionsTags(listAllEvents($dbPipeline));
		?>
	</select>
	User:
	<select id="ev_input_user" name="ev_input_user">
		<?php
			wrapInOptionstags(listAllUsers($dbPipeline));
		?>
	</select>
	<textarea name='ev_input_journal' id='ev_input_journal' cols='80' rows='6' maxlength='999'></textarea>
	<input type="hidden" name="insertingEventComplete" id="insertingEventComplete" value="true">
	<input type="submit" name="submit" id="submit" value="Submit">
</form>
<form name='new_event' id='new_event' method='post'>
	<h2>Add new event type!</h2><br>
	Event:
	<input type='text' name='new_event_eventName' id='new_event_eventName' maxlength='50'>
	Event Category:
	<input type='text' name='new_event_eventCategory' id='new_event_eventCategory' maxlength='30'>
	Event Location:
	<input type='text' name='new_event_eventLocation' id='new_event_eventLocation' maxlength='30'>
	Points:
	<input type='text' name='new_event_points' id='new_event_points'>
	<input type='hidden' name='insertingNewEvent' id='insertingNewEvent' value="true">
	<input type="submit" name="submit" id="submit" value="Submit">
</form>

<form name = "timeIn" id = "timeIn" method = "post">
    Time In:
    User:
    <select id = "timeInId" name = "timeInId">
    <?php
   	 wrapInOptionsTags(listAllUsers($dbPipeline));
    ?>
    </select>
    <input type = "hidden" name = "timeInComplete" id = "timeInComplete" value = "true">
    <input type = "submit" name = "submit" id = "submit" value = "Sigh In">
</form>
<form name = "timeOut" id = "timeOut" method = "post">
    Time Out:
    User:
    <select id = "timeOutId" name = "timeOutId">
    <?php
   	 wrapInOptionsTags(listAllUsers($dbPipeline));
    ?>
    </select>
    <input type = "hidden" name = "timeOutComplete" id = "timeOutComplete" value = "true">
    <input type = "submit" name = "submit" id = "submit" value = "Sigh Out">
</form>


</body>

</html>