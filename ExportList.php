<?php
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or die ("Could not connect");
$sql="SELECT * FROM Comics ORDER BY Title, Volume, Issue, Notes";
$result=mysqli_query($cxn,$sql);

$line1="Title\tVolume\tIssue\tPublicationDate\tNotes\tStory Title\tPublisher\tPrice Paid\tValue\tCondition\n";
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	$data=$data."$Title\t$Volume\t$Issue\t$publicationDate\t$Notes\t$StoryTitle\t$Publisher\t$PricePaid\t$Value\t$Condition\n";
}
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ComicList.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header$line1$data";
?>