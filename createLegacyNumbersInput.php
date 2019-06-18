<?php
session_start();
$cxn=@mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$sql="SELECT DISTINCT Title FROM Comics ORDER BY Title";
$result=mysqli_query($cxn,$sql);
$numAffectedRows=$_GET['num'];
?>
<html>
<head><title>COMICS</title></head>
<body bgcolor="#408080" text="#FFFFFF">
<form method="post" action="createLegacyNumbers.php" >
<h1>Create Legacy Numbers</h1><br>
<table>
<td>Original title:</td>
<td>Title:</td>
<td> <select name ='ogTitle'>
<?php

if(!empty($numAffectedRows))
	echo "<script type='text/javascript'>alert('$numAffectedRows legacy numbers created');</script>";

	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		$insertTitle=str_replace("'","%",$Title);
		echo "<option value='$insertTitle'>$Title</option>\n";
	}
?>
</td>
<td>Volume:</td>
<td><input type="number" size='5' name="OGVolume" value="0"/></td>
<td>Starting issue:</td>
<td><input type="number" size='5' name="OGStartingIssue" value="0"/></td>
<td>Ending issue:</td>
<td><input type="number" size='5' name="OGEndingIssue" value="0"/></td>
</tr>

<td>Legacy numbering:</td>
<td>Title:</td>
<td> <select name ='legacyTitle'>
<?php
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		$insertTitle=str_replace("'","%",$Title);
		echo "<option value='$insertTitle'>$Title</option>\n";
	}
?></td>
<td>Volume:</td>
<td><input type="number" size='5' name="legacyVolume" value="0"/></td>
<td>Starting issue:</td>
<td><input type="number" size='5' name="legacyStartingIssue" value="0"/></td>
</tr>


</table>
<input type='submit' value='Search' /><br>
<a href='menu.php'>Back to main menu</a> <br>
<a href='logout.php'>Logout</a> <br>
</body></html>