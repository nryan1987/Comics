<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$ID=$_GET['characterID'];
$name=$_GET['character'];
$pic=$_GET['picture'];
$newAlias=$_GET['newAlias'];
$aliasFields=$_GET['aliasFields'];

for ($i=0;$i<count($aliasFields);$i++) 
{
	$deleteAliasSQL="DELETE FROM CharacterAliases WHERE CharacterID='$ID' AND Alias='$aliasFields[$i]'";
	mysqli_query($cxn,$deleteAliasSQL)or die("Could not delete alias");
}

$updateSQL="UPDATE Characters SET Characters.Characters=\"$name\", Characters.CharacterPic='$pic'
WHERE CharacterID='$ID'";
mysqli_query($cxn,$updateSQL)or die("Could not update");
if(!(empty($newAlias)))
{
	$insertAlias="INSERT INTO CharacterAliases VALUES('$ID', '$newAlias')";
	mysqli_query($cxn,$insertAlias)or die("Could not update");
}
mysqli_query($cxn,$updateSQL)or die("Could not update");
header("Location: updateCharacterInput.php?id=$ID");
?>