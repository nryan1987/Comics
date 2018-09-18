<?php
include 'utilities.php';
session_cache_limiter('private_no_expire');
session_start();
ini_set('session.cache_limiter', 'private');
@$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
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
$pic=$_POST['Picture'];
$queryLimit=$_GET['end'];
$new=$_GET['new'];
$old=$_GET['old'];
$nq=$_GET['nq'];
$sql="SELECT *, (SELECT GROUP_CONCAT(Notes SEPARATOR '; ') FROM Notes WHERE Notes.ComicID=Comics.ComicID ORDER BY Notes.Notes) AS Notes FROM Comics WHERE";
$count=0;
$SumOfPricePaid=0;
$title=str_replace("%","'",$title);

$cookie_name=$_SESSION['uname']."_search";

if(($new!=0)||(!(empty($new))))
{
//	$sql="SELECT * FROM Comics WHERE `ComicID` BETWEEN ".$old." AND ".$new;
	$whereClause = " `ComicID` BETWEEN ".$old." AND ".$new;
	//$totalPaidResult=mysqli_query($cxn,"SELECT Sum(Comics.PricePaid) AS SumOfPricePaid, Avg(Comics.PricePaid) AS AveragePricePaid,
	//Min(Comics.PricePaid) AS MinPricePaid, Max(Comics.PricePaid) AS MaxPricePaid FROM Comics WHERE `ComicID` BETWEEN ".$old." AND ".$new);
	//$totalPaidRow=mysqli_fetch_assoc($totalPaidResult);
	//extract($totalPaidRow);
	$sql=$sql.$whereClause;
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql".mysqli_error($cxn));
	$numIssues=mysqli_num_rows($result);
	//echoPage("Search Results");
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
	
	//publicationDate
	if(!((empty($month)) && (empty($year))))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		$whereClause=$whereClause." `publicationDate` $yearOp '$year$month'";
		$count++;
	}	
	else if(!(empty($month)))
	{
		$mnth=getMonthNum($month);
		if($count>0)
			$whereClause=$whereClause." AND ";
		//$whereClause=$whereClause." `Month`=\"$month\"";
		$whereClause=$whereClause." MONTH(`publicationDate`) $yearOp $mnth";
		$count++;
	}
	else if(!(empty($year)))
	{
		if($count>0)
			$whereClause=$whereClause." AND ";
		//$whereClause=$whereClause." `Year` LIKE \"%$year%\"";
		$whereClause=$whereClause." YEAR(`publicationDate`) $yearOp $year";
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
		$whereClause=$whereClause." `StoryTitle` LIKE \"%$story%\"";
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
	if(!empty($pic))
	{
		$picSQL="SELECT ComicID, Picture FROM Comics";
		$picResult=mysqli_query($cxn,$picSQL)or die("Could not execute pic Search: $picSQL");
		
		$idCount = 0;
		$idClause=" `ComicID` IN ( ";
		$ids="";
		
		while($row=mysqli_fetch_assoc($picResult))
		{
			extract($row);
			
			if(empty($Picture))
			{
				$ids = $ids." $ComicID, ";
				$idCount++;
			}
			else
			{
				$fullURL = substr($Picture, 3);
				$fullURL = $_SERVER['DOCUMENT_ROOT']."/".$fullURL; //gives full path.
				
				$fileExists = file_exists($fullURL);
				if(!$fileExists)
				{
					$ids = $ids." $ComicID, ";
					$idCount++;
				}
			}
		}
		
		if($idCount > 0)
		{
			$ids=substr($ids,0,-2);//Cuts off the final unnecessary comma.
			if($count>0)
				$whereClause=$whereClause." AND ";
			$whereClause=$whereClause.$idClause.$ids.")";
			
			$count++;
		}
	}

	if($count == 0)
	{
		$whereClause=" 1";
	}
	
	logEvent($cxn, "Search: ".htmlspecialchars($whereClause));
	$sql=$sql.$whereClause." ORDER BY Title, Volume, Issue, Notes";

	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql");
	$resNum=mysqli_num_rows($result);
	
//	$totalPaidResult=mysqli_query($cxn,"SELECT Sum(Comics.PricePaid) AS SumOfPricePaid, Avg(Comics.PricePaid) AS AveragePricePaid,
//	Min(Comics.PricePaid) AS MinPricePaid, Max(Comics.PricePaid) AS MaxPricePaid FROM Comics WHERE ".$whereClause);
//	$totalPaidRow=mysqli_fetch_assoc($totalPaidResult);
//	extract($totalPaidRow);
	
	$success = setcookie($cookie_name, $whereClause, time() + (86400 * 30), "/"); // 86400 = 1 day
	$_SESSION['queryWhere'] = $whereClause;

	//Query to get numIssues
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql");
	$numIssues=mysqli_num_rows($result);
	
	//Add limit and re-run the query.
	$sql=$sql." LIMIT 0 , 150";
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql");
}
else if(!(empty($queryLimit)))
{
	//$whereClause=$_COOKIE[$cookie_name];
	$whereClause = $_SESSION['queryWhere'];
	$sql=$sql." ".$whereClause." ORDER BY Title, Volume, Issue, Notes";
	$lowerLimit=$queryLimit-150;
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql. ".mysqli_error($cxn));
	$numIssues=mysqli_num_rows($result);
	//echoPage("Search Results");
	//displayPages($numIssues);

//	$totalPaidResult=mysqli_query($cxn,"SELECT Sum(Comics.PricePaid) AS SumOfPricePaid, Avg(Comics.PricePaid) AS AveragePricePaid,
//	Min(Comics.PricePaid) AS MinPricePaid, Max(Comics.PricePaid) AS MaxPricePaid FROM Comics WHERE ".$whereClause);
//	$totalPaidRow=mysqli_fetch_assoc($totalPaidResult);
//	extract($totalPaidRow);

	$sql=$sql." LIMIT $lowerLimit , 150";
	$result=mysqli_query($cxn,$sql)or die("Could not execute Search: $sql. ".mysqli_error($cxn));
}


echo "<html>
<head><title>COMICS</title></head>

<body bgcolor=\"#408080\" text=\"#FFFFFF\">
<form action='update.php' method='POST'>
<h1>Search Results</h1><br>
<a href='menu.php'>Back to main menu</a> <br>
<a href='search.php'>Back to search</a> <br>";
if($numIssues==1)
{
	echo "<br>Your search matches $numIssues issue.<br>";
}
else
{
	echo "<br>Your search matches $numIssues issues.<br>";
}

$totalPaidResult=mysqli_query($cxn,"SELECT Sum(Comics.PricePaid) AS SumOfPricePaid, Avg(Comics.PricePaid) AS AveragePricePaid,
	Min(Comics.PricePaid) AS MinPricePaid, Max(Comics.PricePaid) AS MaxPricePaid FROM Comics WHERE ".$whereClause);
	$totalPaidRow=mysqli_fetch_assoc($totalPaidResult);
extract($totalPaidRow);
echo "Totals for your search. Total: $$SumOfPricePaid\tAverage: $$AveragePricePaid\tMinimum: $$MinPricePaid\tMaximum: $$MaxPricePaid<br>";
displayPages($numIssues);
echo "<br>";
displayComics($cxn,$result);

displayPages($numIssues);
echo "<br><a href='menu.php'>Back to main menu</a> <br>";

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