<?php
session_start();
$cxn=@mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$deleteSearch="TRUNCATE TABLE searchQuery";
mysqli_query($cxn,$deleteSearch) or die ("Could not delete searches. $deleteSearch");
$sql="SELECT DISTINCT Title FROM Comics ORDER BY Title";
$result=mysqli_query($cxn,$sql);
?>
<html>
<head><title>COMICS</title></head>

<body bgcolor="#408080" text="#FFFFFF">
<form action='searchResults.php?nq=1' method='POST'>
<h1>Search</h1><br>
<table>
<tr>
<td>ComicID: </td>
<td><input type="number" size='5' name="comicID" /></td>
</tr>
<tr>
<td>Title:</td>
<td> <select name ='Title'>
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
<td>Title (Keyword search):</td>
<td><input type="text" size='30' name="altTitle" /></td>
</tr>
<tr>
<td>Issue:</td>
<td><input type="number" size='5' name="issue"/></td>
</tr>
<tr>
<td>Volume:</td>
<td><input type="number" size='5' name="volume"/></td>
</tr>
<td>Date:</td>
<td>
<select name ='yearOperator'>
<option value='='>=</option>
<option value='<'><</option>
<option value='<='><=</option>
<option value='>'>></option>
<option value='>='>>=</option>
<option value='!='>NOT</option>
</select>
<select name ='Month'>
<option value=''></option>




<option value='-01-01'>January</option>
<option value='-02-01'>February</option>
<option value='-03-01'>March</option>
<option value='-04-01'>April</option>
<option value='-05-01'>May</option>
<option value='-06-01'>June</option>
<option value='-07-01'>July</option>
<option value='-08-01'>August</option>
<option value='-09-01'>September</option>
<option value='-10-01'>October</option>
<option value='-11-01'>November</option>
<option value='-12-01'>December</option>
<option value='-03-20'>Spring</option>
<option value='-06-21'>Summer</option>
<option value='-09-22'>Fall</option>
<option value='-12-23'>Winter</option>
<option value='-12-30'>Annual</option>
<option value='-01-31'>Original Graphic Novel</option>
</select>
<input type="number" size='5' name="year"/></td>
</tr>
<td>Story Title:</td>
<td><input type="text" size='33' name="storyTitle" /></td>
</tr>
<td>Publisher:</td>
<td><select name ='Publisher'>
<option value=''></option>
<?php
	$sql="SELECT DISTINCT Publisher FROM Publisher ORDER BY Publisher";
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		$searchPublisher=str_replace("'","%",$Publisher);
		echo "<option value='$searchPublisher'>$Publisher</option>\n";
	}
?>
</select></td>
</tr>
<td>Price Paid:</td>
<td>
<select name ='pricePaidOperator'>
<option value='='>=</option>
<option value='<'><</option>
<option value='<='><=</option>
<option value='>'>></option>
<option value='>='>>=</option>
<option value='!='>NOT</option>
</select>
$<input type="number" size='5' name="pricePaid"/></td>
</tr>
<td>Value:</td>
<td>
<select name ='valueOperator'>
<option value='='>=</option>
<option value='<'><</option>
<option value='<='><=</option>
<option value='>'>></option>
<option value='>='>>=</option>
<option value='!='>NOT</option>
</select>
$<input type="number" size='5' name="value"/></td>
</tr>
<td>Condition:</td>
<td><select name ='Condition'>
<option value=''></option>
<?php
	$sql="SELECT `Condition` FROM `Condition` ORDER BY `ConditionID`";
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		echo "<option value='$Condition'>$Condition</option>\n";
	}
?>
</select></td>
</tr>
<td>Picture:</td>
<td><select name ='Picture'>
<option value=''></option>
<option value='isNull'>is null</option>
</select></td>
<td>Notes (optional):</td>
<td><input type="text" size='30' name="notes" /></td>
</table> 
<input type='submit' value='Search' /><br>
<a href="menu.php">Back to main menu</a> <br>
<a href='logout.php'>Logout</a> <br>
</form></body></html>