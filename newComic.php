<?php
session_start();
$cxn=@mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");
$sql="SELECT DISTINCT Title FROM Comics ORDER BY Title";
$result=mysqli_query($cxn,$sql);
$comicCount="SELECT COUNT(ComicID) as comicCount FROM Comics";
$result2=mysqli_query($cxn,$comicCount)or die("Could not count comics");
$row2=mysqli_fetch_assoc($result2);
extract($row2);
$comicCount=$comicCount+1;
?>
<html>
<head><title>COMICS</title></head>

<body bgcolor="#408080" text="#FFFFFF">
<form action='processform.php' method='POST'>
<table>
<tr>
<td>ComicID: </td>
<td><input type="number" size='5' name="comicID" value=<?php echo "$comicCount"; ?> /></td>
</tr>
<tr>
<td>Title:</td>
<td> <select name ='Title'>
<?php
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		$insertTitle=str_replace("'","%",$Title);
		echo "<option value='$insertTitle'>$Title</option>\n";
	}
?>
</select></td>
<td>Or enter a new title:</td>
<td><input type="text" size='30' name="alternateTitle" /></td>
</tr>
<tr>
<td>Issue:</td>
<td><input type="number" size='5' name="issue" value="0"/></td>
</tr>
<tr>
<td>Volume:</td>
<td><input type="number" size='5' name="volume" value="1"/></td>
</tr>
<td>Date:</td>
<td><select name ='Month'>
<option value='January'>January</option>
<option value='February'>February</option>
<option value='March'>March</option>
<option value='April'>April</option>
<option value='May'>May</option>
<option value='June'>June</option>
<option value='July'>July</option>
<option value='August'>August</option>
<option value='September'>September</option>
<option value='October'>October</option>
<option value='November'>November</option>
<option value='December'>December</option>
<option value='Spring'>Spring</option>
<option value='Summer'>Summer</option>
<option value='Fall'>Fall</option>
<option value='Winter'>Winter</option>
<option value='ANNUAL'>ANNUAL</option>
<option value='Original Graphic Novel'>Original Graphic Novel</option>
</select>
<input type="number" size='5' name="year" value=<?php echo date("Y"); ?> /></td>
</tr>
<td>Story Title:</td>
<td><input type="text" size='33' name="storyTitle" /></td>
</tr>
<td>Publisher:</td>
<td><select name ='Publisher'>
<?php
	$sql="SELECT DISTINCT Publisher FROM Publisher ORDER BY Publisher";
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		echo "<option value=\"$Publisher\">$Publisher</option>\n";
	}
?>
</select></td>
<td>Or enter a new publisher:</td>
<td><input type="text" size='30' name="alternatePublisher" /></td>
</tr>
<td>Price Paid:</td>
<td>$<input type="number" step="any" size='5' name="pricePaid" value="0.00"/></td>
</tr>
<td>Value:</td>
<td>$<input type="number" step="any" size='5' name="value" value="0.00"/></td>
</tr>
<td>Condition:</td>
<td><select name ='Condition'>
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
<td>Notes (optional):</td>
<td><input type="text" size='30' name="notes" /></td>
</table> 
<input type='submit' value='Add comic' /><br><br>
<a href="http://ryan-brannan.com/menu.php">Back to main menu</a> <br>
<a href="" onclick="window.open('uploadFile.php','', 'width=400, height=250, location=yes, menubar=yes, status=yes, toolbar=yes, scrollbars=no, resizable=no'); return false">
   Add comics from an excel spreadsheet</a> <br><br>
<a href="http://ryan-brannan.com/logout.php">Logout</a> <br>
</form></body></html>