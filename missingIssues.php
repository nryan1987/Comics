<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$Title=$_POST['Title'];
$Volume=$_POST['Volume'];
if(empty($Volume))
	$Volume=1;
logEvent($cxn, "Find missing issues. $Title volume $Volume.");

$maxSql="SELECT MAX(Issue) as max FROM Comics WHERE Title=\"$Title\" and Volume=$Volume";
$result=mysqli_query($cxn,$maxSql);
$row=mysqli_fetch_assoc($result);
extract($row);
$deleteSearch="TRUNCATE TABLE missingIssues";
mysqli_query($cxn,$deleteSearch) or die ("Could not delete searches. $deleteSearch");
?>
<html>
<head><title>COMICS</title></head>
<body bgcolor="#408080" text="#FFFFFF">
<?php
	$sql="INSERT INTO missingIssues (`Title`, `Issue`) VALUES ";
	for($x=1; $x<=$max; $x++)
	{
		$values="(\"".$Title."\", ".$x."), ";
		$sql=$sql.$values;
	}
	$sql=substr($sql,0,-2);//Cuts off the final unnecessary comma.
	$result=mysqli_query($cxn,$sql);
	
	$sql="SELECT missingIssues.Issue
		  FROM missingIssues
		  LEFT JOIN 
		  (
		     SELECT Comics.Issue
			 FROM Comics
			 WHERE Title=\"$Title\" AND Volume=$Volume
		  ) AS Comics
		  ON missingIssues.Issue = Comics.Issue
		  WHERE Comics.Issue IS NULL ORDER BY Issue DESC";
	$result=mysqli_query($cxn,$sql);
	$numRows=mysqli_num_rows($result);
	
	if($numRows == 1)
		echo "The collection is missing $numRows issue of $Title volume $Volume from #1 to #$max<br>";
	else
		echo "The collection is missing $numRows issues of $Title volume $Volume from #1 to #$max<br>";
	echo "<table style='border=\"0\"'>" ;
	echo "<tr>";
	$count = 0;
	while($row=mysqli_fetch_assoc($result))
	{
		$count ++;
		extract($row);
		echo "<td>$Issue</td>";
		if($count % 20 == 0)
			echo "</tr><tr>";
	}
	echo "</tr></table>";

echo "<table style='width:100%'>" ;
echo "<td><a href='findMissingIssues.php'>Find more missing issues</a></td></tr>";
echo "<td><a href='menu.php'>Back to main menu</a></td></tr>";
echo "<td><a href='logout.php'>Logout</a></td></tr>";
echo "</table>\n";

$deleteSearch="TRUNCATE TABLE missingIssues";
mysqli_query($cxn,$deleteSearch) or die ("Could not delete searches. $deleteSearch");
?>