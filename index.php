<html>
<head>
<?php
include_once 'dbconnect.php';
include_once 'DBPrototypeLibrary.php';
?>
</head>

<body>
<?php
if(isset($_POST['loginSubmit'])){
	login($dbPipeline,$_POST['username'],$_POST['password']);
}
if(isset($_POST['forgotPasswordSubmit'])){
	resetPassword($dbPipeline,$_POST['r_username'],$_POST['user_input_day'],$_POST['user_input_month'],$_POST['user_input_year'],$_POST['newPassword']);
}
?>


<h2>Log In</h2>

<form name='login' id='login' method='post'>
Username: <input type='text' name='username' id='username' value='' maxlength='30'><br><br>
Password: <input type='text' name='password' id='password' value='' maxlength='30'><br><br>
<input type='submit' name='loginSubmit' id='loginSubmit' value='Log In'>
</form>

<h3>Forgot Password</h3>

<form name='forgotPassword' id='forgotPassword' method='post'>
Username: <input type='text' name='r_username' id='r_username' value='' maxlength='30'><br><br>
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
New Password: <input type='password' name='newPassword' id='newPassword' value='' maxlength='30'><br><br>
<input type='submit' name='forgotPasswordSubmit' id='forgotPasswordSubmit' value='Change Password'>
</form>
</body>
</html>