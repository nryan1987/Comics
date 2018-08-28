<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$sql="SELECT DISTINCT Title FROM Comics ORDER BY Title";
$result=mysqli_query($cxn,$sql);
?>
<html>
<head><title>COMICS</title></head>
<body bgcolor="#408080" text="#FFFFFF">
<form method="post" action="missingIssues.php" >
<h1>Find missing issues</h1><br>
Title: 
<select name ='Title'>
<option value=''></option>
<?php
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		$searchTitle=str_replace("'","%",$Title);
		echo "<option value='$searchTitle'>$Title</option>\n";
	}
?>
</select>
<br>Volume:
<input type="number" size='5' name="Volume"/><br>
<input type='submit' value='Search' /><br>
<a href='menu.php'>Back to main menu</a> <br>
<a href='logout.php'>Logout</a> <br>
</body></html>