<?php
$dbPipeline = mysqli_connect('HOST NAME', 'USERNAME', 'PASSWORD', 'DATABASE NAME');
if(!$dbPipeline){
	die('Error: ' . mysqli_connect_errno() . '.');
}
?>