<?php
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$ogTitle=$_POST['ogTitle'];
$ogVolume=$_POST['OGVolume'];
$ogStartingIssue=$_POST['OGStartingIssue'];
$ogEndingIssue=$_POST['OGEndingIssue'];
$legacyTitle=$_POST['legacyTitle'];
$legacyVolume=$_POST['legacyVolume'];
$legacyStartingIssue=$_POST['legacyStartingIssue'];

$createLegacySQL="INSERT INTO ComicAlias (ComicID, Title, Issue, Volume) VALUES";
$ogSQL="SELECT ComicID, Title, Issue FROM Comics WHERE Title=\"$ogTitle\" AND Volume=$ogVolume AND Issue BETWEEN $ogStartingIssue AND $ogEndingIssue ORDER BY Issue ASC";
$ogIssuesResult = mysqli_query($cxn,$ogSQL)or die("Could not search comics.<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$ogSQL."\n".mysqli_error());
while($row=mysqli_fetch_assoc($ogIssuesResult))
{
	extract($row);
	$i=$legacyStartingIssue + $Issue - 1;
	$createLegacySQL=$createLegacySQL." ($ComicID, \"$legacyTitle\", $i, $legacyVolume), ";
}
$createLegacySQL=substr($createLegacySQL,0,-2);//Cuts off the final unnecessary comma.
$createLegacySQL=$createLegacySQL." on duplicate key UPDATE Title=VALUES(Title), Volume=VALUES(Volume), Issue=VALUES(Issue)";
mysqli_query($cxn,$createLegacySQL)or die("Could not add comic alias.<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$createLegacySQL."\n".mysqli_error());
$n=mysqli_affected_rows($cxn);
header("Location: createLegacyNumbersInput.php?num=$n");
?>