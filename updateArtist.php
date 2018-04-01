<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$ID=$_GET['artistID'];
$name=$_GET['Artist'];
$aka=$_GET['creditedAs'];
$pic=$_GET['picture'];
$aliasFields=$_GET['aliasFields'];

for ($i=0;$i<count($aliasFields);$i++) 
{
	$deleteAliasSQL="DELETE FROM ArtistAlias WHERE ArtistID='$ID' AND Alias='$aliasFields[$i]'";
	mysqli_query($cxn,$deleteAliasSQL)or die("Could not delete alias for artist");
}

$updateSQL="UPDATE Artists SET Artists.Artist=\"$name\", Artists.artistPic='$pic' WHERE ArtistID='$ID'";
mysqli_query($cxn,$updateSQL)or die("Could not update. ".mysqli_error($cxn));
if(!empty($aka))
{
	$newAliasSQL="INSERT INTO ArtistAlias VALUES('$ID', \"$aka\")";
	mysqli_query($cxn,$newAliasSQL)or die("Could not add new alias for artist. ".mysqli_error($cxn));
}
header("Location: updateArtistInput.php?id=$ID");
?>