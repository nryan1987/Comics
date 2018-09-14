<?php
include 'utilities.php';
session_start();
$cxn=@mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
?>
<html>
<head><title>User Log</title><link rel="shortcut icon" href=""></head>

<body bgcolor="#408080" text="#FFFFFF">
<h1>User Log</h1><br>

<?php
	//Get the current time stamp from the db. This time stamp could be different from the system time stamp.
	//The db time stamp is displayed to more easily compare with log entries.
	$timeStampSQL = "SELECT CURRENT_TIMESTAMP";
	$ts=mysqli_query($cxn,$timeStampSQL)or die("Could not execute time stamp search. ".mysqli_error($cxn));
	$tsRow=mysqli_fetch_assoc($ts);
	extract($tsRow);

	echo "Current date/time: ".$CURRENT_TIMESTAMP."<br>";
	$userLogSQL="SELECT * FROM UserLog ORDER BY TimeStamp DESC";
		
	$userLogResults=mysqli_query($cxn,$userLogSQL)or die("Could not execute user log search. ".mysqli_error($cxn));
	
	
	echo "<table Border='1' style=\"color:white\">";
	echo "<td>User Name</td>";
	echo "<td>Event</td>";
	echo "<td>Time Stamp</td></tr>";	
	
	while($userLogRow=mysqli_fetch_assoc($userLogResults))
	{
		extract($userLogRow);
		echo "<td>$UserName</td>";
		echo "<td>$Event</td>";
		echo "<td>$TimeStamp</td></tr>";
	}
?>