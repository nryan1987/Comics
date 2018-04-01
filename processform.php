<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$CID=$_POST['comicID'];
$issueNum=$_POST['issue'];
$issueVol=$_POST['volume'];
$month=$_POST['Month'];
$year=$_POST['year'];
$notes=$_POST['notes'];
$story=$_POST['storyTitle'];
$paid=$_POST['pricePaid'];
$value=$_POST['value'];
$grade=$_POST['Condition'];

if(empty($_POST['comicID']))
{
	header("Location: newComic.php");
}
if(empty($_POST['alternateTitle']))
{
	$title=$_POST['Title'];
	$title=str_replace("%","'",$title);
}
else
{
	$title=$_POST['alternateTitle'];
}
if(empty($_POST['alternatePublisher']))
{
	$publisher=$_POST['Publisher'];
}
else
{
	$publisher=$_POST['alternatePublisher'];
	$publisherCount="SELECT COUNT(PublisherID) as publisherCount FROM Publisher";
	$result=mysqli_query($cxn,$publisherCount)or die("Could not count publishers");
	$row=mysqli_fetch_assoc($result);
	extract($row);
	$publisherCount=$publisherCount+1;
	$insertPublisher="INSERT INTO Publisher (PublisherID, Publisher) VALUES ('$publisherCount', '$publisher')";
	mysqli_query($cxn,$insertPublisher)or die("Could not insert into publisher");
}
$url=getURL($title, $issueVol, $issueNum, $notes);

$fullURL = substr($url, 3);
$fullURL = $_SERVER['DOCUMENT_ROOT']."/".$fullURL; //gives full path.
//echo $fullURL;
$urlExists = file_exists($fullURL);

if(!$urlExists)
{
	//echo "URL Does not exist.";
	$url="";
}
//else
	//echo "URL Does exist.";

$insertComic="INSERT INTO Comics (`ComicID`, `Title`, `Volume`, `Issue`, `Month`, `Year`, `StoryTitle`, `Publisher`, `PricePaid`, `Value`, `Condition`, `Picture`) 
VALUES ('$CID', \"$title\", '$issueVol', '$issueNum', '$month', '$year', \"$story\", \"$publisher\", '$paid', '$value', '$grade', '$url')";
mysqli_query($cxn,$insertComic)or die("Could not insert into Comics. $insertComic");

if($notes != Null)
{
	$insertNotesSQL = "INSERT INTO Notes (ComicID, Notes) VALUES ($CID, \"$notes\")";
	mysqli_query($cxn,$insertNotesSQL)or die("Could not insert into notes<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$insertNotesSQL."\n".mysqli_error());
}
?>
<html>
<head><title><?php echo $title." Volume ".$issueVol." "." #".$issueNum ?></title></head>
<body bgcolor="#408080" text="#FFFFFF">

<table Border='1'>
<tr>
<td>ComicID: </td>
<td><?php echo "$CID" ?></td>
</tr>
<tr>
<td>Title:</td>
<td><?php echo "$title" ?></td>
</tr>
<tr>
<td>Issue:</td>
<td><?php echo "$issueNum" ?></td>
</tr>
<tr>
<td>Volume:</td>
<td><?php echo "$issueVol" ?></td>
</tr>
<td>Date:</td>
<td><?php echo "$month, $year" ?></td>
</tr>
<td>Story Title:</td>
<td><?php echo "$story" ?></td>
</tr>
<td>Publisher:</td>
<td><?php echo "$publisher" ?></td>
</tr>
<td>Price Paid:</td>
<td><?php echo "$$paid" ?></td>
</tr>
<td>Value:</td>
<td><?php echo "$$value" ?></td>
</tr>
<td>Condition:</td>
<td><?php echo "$grade" ?></td>
</tr>
<td>Notes (optional):</td>
<td><?php echo "$notes" ?></td>
</tr>
<td>Picture:</td>
<td><?php echo "$url" ?></td>
</table> 
<?php echo '<img STYLE="position:absolute; TOP:7px; LEFT:750px;" src="'.$url.'" ALT="Picture unavailable">' ?>
<a href=update.php?id=<?php echo "$CID" ?>>Update <?php echo "$CID" ?> </a><br>
<a href="menu.php">Back to main menu</a> <br>
</body></html>