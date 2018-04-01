<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");
$ID=$_GET['writerID'];
$name=$_GET['Writer'];
$aka=$_GET['creditedAs'];
$pic=$_GET['picture'];
$aliasFields=$_GET['aliasFields'];

for ($i=0;$i<count($aliasFields);$i++) 
{
	$deleteAliasSQL="DELETE FROM WriterAlias WHERE WriterID='$ID' AND Alias='$aliasFields[$i]'";
	mysqli_query($cxn,$deleteAliasSQL)or die("Could not delete alias for writer");
}

$updateSQL="UPDATE Writers SET Writers.Writer=\"$name\", Writers.writerPic='$pic' WHERE WriterID='$ID'";
mysqli_query($cxn,$updateSQL)or die("Could not update");
if(!empty($aka))
{
	$newAliasSQL="INSERT INTO WriterAlias VALUES('$ID', \"$aka\")";
	mysqli_query($cxn,$newAliasSQL)or die("Could not add new alias. ".mysqli_error($cxn));
}
header("Location: updateWriterInput.php?id=$ID");
?>