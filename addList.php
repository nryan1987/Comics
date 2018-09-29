<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or die ("Could not connect");
include 'utilities.php';

echo "Upload: " . $_FILES["file"]["name"] . "<br />";
echo "Type: " . $_FILES["file"]["type"] . "<br />";
echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
$file=fopen($_FILES["file"]["tmp_name"],"r") or exit("Cannot open file ".$_FILES["file"]["name"]);
	
$comicCount="SELECT MAX(ComicID) as comicCount FROM Comics";
if($cxn==null)
	echo "cxn is null.";
$countResult=mysqli_query($cxn,$comicCount)or die("Could not count comics");
$countRow=mysqli_fetch_assoc($countResult);
extract($countRow);
$comicCount=$comicCount+1;
$totalAdded=0;
$notesPresent=0;

$sql="INSERT INTO Comics (`ComicID`, `Title`, `Volume`, `Issue`, `publicationDate`, `StoryTitle`, `Publisher`, `PricePaid`, `Value`, `Condition`, `Picture`) VALUES ";
$insertNotesSQL = "INSERT INTO Notes (ComicID, Notes) VALUES ";
$logString = "Enter list. ";
	
while(!feof($file))
{
	$line=htmlspecialchars(fgets($file));
	//list($title,$vol,$num,$notes,$pub,$paid)=split("\t",$line,6); //Deprecated and deleted in php 7.0
	list($title,$vol,$num,$notes,$pub,$paid) = explode("\t", $line);
	
	$title = trim($title);
	$paid=str_replace("$","",$paid);
	$paid=str_replace("-","0.00",$paid);
	if(empty($vol))
		$vol=1;
	
	if(!empty($title))
	{
		if(empty($pub))
		{
			$publisherSQL="SELECT DISTINCT Publisher FROM `Comics` WHERE Title =\"$title\"";
			$publisherResult=mysqli_query($cxn,$publisherSQL);
					
			if($publisherResult->num_rows==0)
			{
				$pub="12 Gauge Comics"; //Default
			}
			else	
			{
				$row=mysqli_fetch_assoc($publisherResult);
				extract($row);
				$pub=$Publisher;
			}
		}
		else
		{
			$publisherSQL="SELECT Publisher FROM `Publisher` WHERE Publisher =\"$pub\"";
			$publisherResult=mysqli_query($cxn,$publisherSQL);
			if($publisherResult->num_rows==0) //Publisher is not in the database.
			{
				addNewPublisher($cxn, $pub); //Adds the new publisher to the database.
			}
		}

		$url=getURL($title, $vol, $num, $notes);
		$fullURL = substr($url, 3);		
		$fullURL = $_SERVER['DOCUMENT_ROOT']."/".$fullURL; //gives full path.
		//echo $fullURL;
		$fileExists = file_exists($fullURL);

		if($fileExists)
		{
			//echo "File Exists";
			$values="(".$comicCount.",\"".$title."\", ".$vol.", ".$num.", '".date("Y-m-1")."', '', \"".$pub."\", ".$paid.", 0.00, \"MT 10.0\", \"".$url."\"), ";
		}
		else
		{
			//echo "File does not exist";
			$values="(".$comicCount.",\"".$title."\", ".$vol.", ".$num.", '".date("Y-m-1")."', '', \"".$pub."\", ".$paid.", 0.00, \"MT 10.0\", \"\"), ";
		}
		
		if(!empty($notes))
		{
			$notesPresent=1;
			$noteValues="(".$comicCount.", \"".$notes."\"), ";
			$insertNotesSQL=$insertNotesSQL.$noteValues;
		}
		
		$logString=$logString."ID=$comicCount, title=$title, issueNum=$num, issueVol=$vol. ";
		
		$sql=$sql.$values;
		$comicCount++;
		$totalAdded++;
	}
	else
		break;
}
$sql=substr($sql,0,-2);//Cuts off the final unnecessary comma.
$insertNotesSQL=substr($insertNotesSQL,0,-2);//Cuts off the final unnecessary comma.

//echo $sql;
//echo $insertNotesSQL;
mysqli_query($cxn,$sql)or die("Could not insert comics.<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$sql);
logEvent($cxn, $logString);

if($notesPresent==1)
	mysqli_query($cxn,$insertNotesSQL)or die("Could not insert into notes.<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$insertNotesSQL);

echo $totalAdded." comics have been added.\nThe new count is ".--$comicCount."<br>";
$oldNum=$comicCount-$totalAdded+1;
fclose($file);
echo "<a href='searchResults.php?old=$oldNum&new=$comicCount' target='_blank' >View the new issues.</a> <br>";
?>
<html>
<head><title>COMICS</title></head>
<body bgcolor=#408080 text=#FFFFFF>
<br>
<br>
</body>
</html>