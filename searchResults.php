<?php
include 'utilities.php';
session_cache_limiter('private_no_expire');
session_start();
ini_set('session.cache_limiter', 'private');
@$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");

/********************************************
WARNING!!!! The contents of this file are 
slightly different than the local copy.
See the call to ob_start() below.
********************************************/

$CID=$_POST['comicID'];
$title=$_POST["Title"];
$titleKeyword=$_POST["altTitle"];
$issueNum=$_POST['issue'];
$issueVol=$_POST['volume'];
$month=$_POST['Month'];
$yearOp=$_POST['yearOperator'];
$year=$_POST['year'];
$notes=$_POST['notes'];
$story=$_POST['storyTitle'];
$publisher=$_POST['Publisher'];
$paid=$_POST['pricePaid'];
$value=$_POST['value'];
$grade=$_POST['Condition'];
$queryLimit=$_GET['end'];
$new=$_GET['new'];
$old=$_GET['old'];
$nq=$_GET['nq'];
$sql="SELECT *, (SELECT GROUP_CONCAT(Notes SEPARATOR '; ') FROM Notes WHERE Notes.ComicID=Comics.ComicID ORDER BY Notes.Notes) AS Notes FROM Comics WHERE";
$count=0;
$SumOfPricePaid=0;
$title=str_replace("%","'",$title);

$cookie_name=$_SESSION['uname']."_search";

ob_start();
echo "<html>
<head><title>COMICS</title></head>

<body bgcolor=\"#408080\" text=\"#FFFFFF\">
<form action='update.php' method='POST'>
<h1>Search Results</h1><br>
<a href='menu.php'>Back to main menu</a> <br>
<a href='search.php'>Back to search</a> <br>";
if(($new!=0)||(!(empty($new))))
{
	$sql="SELECT * FROM Comics WHERE `ComicID` BETWEEN ".$old." AND ".$new;
	$totalPaidResult=mysqli_query($cxn,"SELECT Sum(Comics.PricePaid) AS SumOfPricePaid, Avg(Comics.PricePaid) AS AveragePricePaid,
	Min(Comics.PricePaid) AS MinPricePaid, Max(Comics.PricePaid) AS MaxPricePaid FROM Comics WHERE `ComicID` BETWEEN ".$old." AND ".$new);
	$totalPaidRow=mysqli_fetch_assoc($totalPaidResult);
	extract($totalPaidRow);
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql");
	$numIssues=mysqli_num_rows($result);
}
//else if($numSearches==0 &&(($new==0)||(!(empty($new)))))
else if($nq == 1)
{
	if(!(empty($CID)))
	{
		$whereClause=" `ComicID`=\"$CID\"";
		$count++;
	}
	if(!(empty($title)))
	{
		$title = mysqli_real_escape_string($cxn, $title);
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Title`=\"$title\"";
		$count++;
	}
	if(!(empty($titleKeyword)) && (empty($title)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Title` LIKE \"%$titleKeyword%\"";
		$count++;
	}
	if(!(empty($issueNum)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Issue`=\"$issueNum\"";
		$count++;
	}
	if(!(empty($issueVol)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Volume`=\"$issueVol\"";
		$count++;
	}
	if(!(empty($month)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Month`=\"$month\"";
		$count++;
	}
	if(!(empty($year)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		//$whereClause=$whereClause." `Year` LIKE \"%$year%\"";
		$whereClause=$whereClause." `Year` $yearOp $year";
		$count++;
	}
	if(!(empty($notes)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." (SELECT GROUP_CONCAT(Notes SEPARATOR '; ') FROM Notes WHERE Notes.ComicID=Comics.ComicID ORDER BY Notes.Notes) LIKE \"%$notes%\"";
		$count++;
	}
	if(!(empty($story)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." (SELECT GROUP_CONCAT(Notes SEPARATOR '; ') FROM Notes WHERE Notes.ComicID=Comics.ComicID ORDER BY Notes.Notes) LIKE \"%$notes%\"";
		$count++;
	}
	if(!(empty($publisher)))
	{
		$publisher=str_replace("%","'",$publisher);
		$publisher = mysqli_real_escape_string($cxn, $publisher);
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Publisher`=\"$publisher\"";
		$count++;
	}
	if(!(empty($paid)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `PricePaid`=\"$paid\"";
		$count++;
	}
	if(!(empty($value)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Value`=\"$value\"";
		$count++;
	}
	if(!(empty($grade)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `Condition`=\"$grade\"";
		$count++;
	}
		
	$sql=$sql.$whereClause." ORDER BY Title, Volume, Issue, Notes";
	//$insertQuery="INSERT INTO Comics.searchQuery (`query`, `whereClause`) VALUES ('$sql', '$whereClause')";
	
	
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql");
	$resNum=mysqli_num_rows($result);
	
	$totalPaidResult=mysqli_query($cxn,"SELECT Sum(Comics.PricePaid) AS SumOfPricePaid, Avg(Comics.PricePaid) AS AveragePricePaid,
	Min(Comics.PricePaid) AS MinPricePaid, Max(Comics.PricePaid) AS MaxPricePaid FROM Comics WHERE ".$whereClause);
	$totalPaidRow=mysqli_fetch_assoc($totalPaidResult);
	extract($totalPaidRow);

	displayPages($resNum);

	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql");
	$numIssues=mysqli_num_rows($result);
	setcookie($cookie_name, $whereClause, time() + (86400 * 30), "/"); // 86400 = 1 day
	
	$sql=$sql." LIMIT 0 , 150";
	
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql");
	//mysqli_query($cxn,$insertQuery)or die("Could not save query. $insertQuery");
}
else if(!(empty($queryLimit)))
{
	$whereClause=$_COOKIE[$cookie_name];
	$sql=$sql." ".$whereClause." ORDER BY Title, Volume, Issue, Notes";
	$lowerLimit=$queryLimit-150;
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql. ".mysqli_error($cxn));
	$numIssues=mysqli_num_rows($result);
	displayPages($numIssues);

	$totalPaidResult=mysqli_query($cxn,"SELECT Sum(Comics.PricePaid) AS SumOfPricePaid, Avg(Comics.PricePaid) AS AveragePricePaid,
	Min(Comics.PricePaid) AS MinPricePaid, Max(Comics.PricePaid) AS MaxPricePaid FROM Comics WHERE ".$whereClause);
	$totalPaidRow=mysqli_fetch_assoc($totalPaidResult);
	extract($totalPaidRow);

	$sql=$sql." LIMIT $lowerLimit , 150";
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql. ".mysqli_error($cxn));
}

if($numIssues==1)
{
	echo "<br>Your search matches $numIssues issue.<br>";
}
else
{
	echo "<br>Your search matches $numIssues issues.<br>";
}
echo "Totals for your search. Total: $$SumOfPricePaid\tAverage: $$AveragePricePaid\tMinimum: $$MinPricePaid\tMaximum: $$MaxPricePaid<br>";
displayComics($cxn,$result);

echo "<a href='menu.php'>Back to main menu</a> <br>";

function displayPages($resNum)
{
	$counter=1;
	$prev=1;
	$displayEnd=0;
	$totalLinks=ceil($resNum/150) + 1;
	while($counter<$totalLinks)
	{
		$end=$counter*150;
		if(($end > $resNum) && ($counter == $totalLinks - 1))
			$end = $resNum;
		
		$displayEnd=$end;
		while($end%150!=0)
			$end++;

		echo("<a href='searchResults.php?end=$end'>$prev - $displayEnd </a>");
		if($counter != ($totalLinks - 1))
			echo ", ";
			
		$counter++;
		$prev=$end;
	}
}
?>
</body></html>