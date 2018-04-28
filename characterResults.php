<?php
session_cache_limiter('private_no_expire');
session_start();
ini_set('session.cache_limiter', 'private');
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"Comics") or die ("Could not connect");
$characterSearch=$_POST['searchWriters'];
$sql="SELECT CharacterID, Characters FROM Characters WHERE CharacterID IN(
SELECT CharacterID FROM Characters WHERE Characters LIKE \"%$characterSearch%\"

UNION

SELECT CharacterID FROM CharacterAliases WHERE Alias LIKE \"%$characterSearch%\") ORDER BY 2";
$result=mysqli_query($cxn,$sql)or die("Could not search characters");

echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
<h1>Character Results</h1><br>
<a href='menu.php'>Back to main menu</a> <br>
<a href=\"logout.php\">Logout</a> <br>
<br>
<br>
</body></html>";
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	$charSQL="SELECT Characters FROM Characters WHERE CharacterID='$CharacterID'";
	$charResult=mysqli_query($cxn,$charSQL)or die("Could not search alias");
	$charRow=mysqli_fetch_assoc($charResult);
	extract($charRow);
	
	$idSQL="SELECT Alias FROM CharacterAliases WHERE CharacterID='$CharacterID' ORDER BY Alias";
	$idResult=mysqli_query($cxn,$idSQL)or die("Could not search alias");
	echo "<table>" ;
	echo "<td><a href=viewCharacter.php?id=$CharacterID>$Characters</a></td>";
	echo "<tr><td colspan='1'><hr /></td></tr>";
	while($idRow=mysqli_fetch_assoc($idResult))
	{
		extract($idRow);
		$aka=urlencode($Alias);
		echo "<td></td><td><a href=viewCharacter.php?id=$CharacterID&alias=$aka>$Alias</a></td></tr>";
	}
}
?>