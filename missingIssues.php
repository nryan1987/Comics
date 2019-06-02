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
?>
<html>
<head><title>COMICS</title></head>
<body bgcolor="#408080" text="#FFFFFF">
<?php
	//Initial population of the missing issues table.
	$sql="CALL populateMissingIssuesTable(\"$Title\", $max)";
	$result=mysqli_query($cxn,$sql);
	
	//Add the aliases to the missing issues table.
	$sql="SELECT DISTINCT Comics.Title as qryTitle, Comics.Issue, Comics.Volume as qryVolume, ComicAlias.Title as aliasTitle, ComicAlias.Issue as aliasIssue, ComicAlias.Volume as aliasVolume
	from ComicAlias inner join Comics on Comics.ComicID=Comics.ComicAlias.ComicID
	where ComicAlias.Title=\"$Title\"";
	$result=mysqli_query($cxn,$sql) or die("Could not add aliases to missing issues table.<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$sql);
	while($row=mysqli_fetch_assoc($result)) {
		extract($row);
		$updateMissingIssues="UPDATE missingIssues SET altTitle=\"$qryTitle\", altIssue=\"$Issue\", altVolume=\"$qryVolume\"
		WHERE Title=\"$aliasTitle\" AND Issue=\"$aliasIssue\"";
		$updateResult=mysqli_query($cxn,$updateMissingIssues);
		$success=mysqli_affected_rows($cxn);
		
		if($success < 1) {
			$updateMissingIssues="UPDATE missingIssues SET altTitle=\"$aliasTitle\", altIssue=\"$aliasIssue\", altVolume=\"$aliasVolume\"
			WHERE Title=\"$qryTitle\" AND Issue=\"$Issue\"";
			$updateResult=mysqli_query($cxn,$updateMissingIssues);
		}
	}
	
	//Carry alt titles forward
	$sql="SELECT Title, Issue, altTitle, altVolume, min(altIssue) as altIssue 
	from missingIssues where altTitle is not NULL 
	GROUP BY Title, Issue, altTitle, altVolume ORDER BY Issue ASC";
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result)) {
		extract($row);

		$sql="INSERT INTO missingIssues (Title, Issue, altTitle, altVolume, altIssue) VALUES";
		$values=" ";
				
		$offset=0;
		if($Issue > $altIssue)
		{
			if($altIssue > 1)
			{
				$offset=$Issue - $altIssue;
			}
			else if($altIssue == 1)
			{
				$offset=$Issue;
			}
			
			$counter=1;
			for($i=$offset + 1; $i<=$max; $i++) {
				$values=$values."(\"$Title\", $i, \"$altTitle\", $altVolume, ".$counter++."), ";
			}
		}
		else if($Issue < $altIssue)
		{
			if($Issue > 1)
			{
				$offset=$altIssue - $Issue + 1;
			}
			if($Issue == 1)
			{
				$offset=$altIssue;
			}
			
			for($i=1; $i<=$max; $i++) {
				$values=$values."(\"$Title\", $i, \"$altTitle\", $altVolume, ".$offset++."), ";
			}
		}
		
		$values=substr($values,0,-2); //cut off the final unnecessary comma.
		$sql=$sql.$values." on duplicate key UPDATE altTitle=VALUES(altTitle), altVolume=VALUES(altVolume), altIssue=VALUES(altIssue)";
		$updateResult=mysqli_query($cxn,$sql);

	}

	$sql="SELECT missingIssues.Issue, missingIssues.altTitle, missingIssues.altVolume, missingIssues.altIssue
		  FROM missingIssues
		  LEFT JOIN 
		  (
			SELECT Comics.Title, Comics.Issue
			FROM Comics
			WHERE Title=\"$Title\"
			UNION
			SELECT Title, Issue from ComicAlias
			where ComicAlias.Title=\"$Title\"
		  ) AS Comics
		  ON (missingIssues.Issue = Comics.Issue and missingIssues.Title=Comics.Title)
		  OR (missingIssues.altIssue = Comics.Issue and missingIssues.altTitle=Comics.Title)
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
		$missingIssue=empty($altTitle) ? $Issue : $Issue." (".$altTitle." VOL.".$altVolume." #".$altIssue.")";
		echo "<td>$missingIssue</td>";
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