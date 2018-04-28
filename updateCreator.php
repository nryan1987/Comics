<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"Comics") or header("Location: index.php?login=false");
$ID=$_GET['artistID'];
$name=$_GET['Artist'];
$aka=$_GET['creditedAs'];
$pic=$_GET['picture'];
$aliasFields=$_GET['aliasFields'];

for ($i=0;$i<count($aliasFields);$i++) 
{
	$deleteAliasSQL="DELETE FROM CreatorAlias WHERE CreatorID='$ID' AND Alias='$aliasFields[$i]'";
	mysqli_query($cxn,$deleteAliasSQL)or die("Could not delete alias for artist");
}

$updateSQL="UPDATE Creators SET Creators.Creator=\"$name\", Creators.creatorPic='$pic' WHERE CreatorID='$ID'";
mysqli_query($cxn,$updateSQL)or die("Could not update. ".mysqli_error($cxn));
if(!empty($aka))
{
	$newAliasSQL="INSERT INTO CreatorAlias VALUES('$ID', \"$aka\")";
	mysqli_query($cxn,$newAliasSQL)or die("Could not add new alias for artist. ".mysqli_error($cxn));
}
header("Location: updateCreatorInput.php?id=$ID");
?>