<?php
include 'utilities.php';
session_start();
$cxn=@mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"Comics") or header("Location: index.php?login=false");
$page=$_GET['page'];
if(empty($page))
	$sql="SELECT *, (SELECT GROUP_CONCAT(Notes SEPARATOR '; ') FROM Notes WHERE Notes.ComicID=Comics.ComicID ORDER BY Notes.Notes) AS Notes FROM `Comics` ORDER BY Title, Volume, Issue, Notes LIMIT 0, 500";
else
	$sql="SELECT *, (SELECT GROUP_CONCAT(Notes SEPARATOR '; ') FROM Notes WHERE Notes.ComicID=Comics.ComicID ORDER BY Notes.Notes) AS Notes FROM `Comics` ORDER BY Title, Volume, Issue, Notes LIMIT $page, 500";

$result=mysqli_query($cxn,$sql);
$totalsSQL="SELECT Count(Comics.ComicID) AS CountOfComics, Sum(Comics.PricePaid) AS SumOfPricePaid, Sum(Comics.Value) AS SumOfValue,
Avg(Comics.PricePaid) AS AveragePricePaid, Avg(Comics.Value) AS AverageValue FROM Comics";
$totalsResult=mysqli_query($cxn,$totalsSQL);
$totalsRow=mysqli_fetch_assoc($totalsResult);
extract($totalsRow);
echo "<html>";
echo "<body";
echo "<head><title>COMICS</title></head>";
echo "<body bgcolor=\"#408080\" text=\"#FFFFFF\">";
echo "<h1>All Issues</h1><br>";
echo "<a href='menu.php'>Back to main menu</a> <br>";
echo "<a href='logout.php'>Logout</a> <br><br>";
echo "<a href='ExportList.php'>Download List</a> <br>";
echo "<a href='ExportPDF.php'>Download PDF</a> <br>";
echo "<table style=\"color:white\">";
echo "<td>Total Comics: $CountOfComics</td></tr>";
echo "<td>Total Price Paid: $$SumOfPricePaid</td></tr>";
echo "<td>Average Price Paid: $$AveragePricePaid</td></tr>";
echo "<td>Total Value: $$SumOfValue</td></tr>";
echo "<td>Average Value: $$AverageValue</td></tr>";
echo "</table><br>";
echo "<table Border='1' style=\"color:white\">";
echo "<td>Title</td>";
echo "<td>Volume</td>";
echo "<td>Issue</td>";
echo "<td>Publication Date</td>";
echo "<td>Notes</td>";
echo "<td>Story Title</td>";
echo "<td>Publisher</td>";
echo "<td>Price Paid</td>";
echo "<td>Value</td>";
echo "<td>Condition</td>";

echo "Page: ";
$numPages = ceil($CountOfComics/500);
for($i = 0; $i < $numPages; $i++)
{
	$page = 500 * $i;
	$pageNum = $i + 1;
	echo("<a href='viewAllComics.php?page=$page'>$pageNum</a>");
	if($i != ($numPages - 1))
		echo ", ";
}	

while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	//echo $publicationDate;
	$mnth = getMonth($publicationDate);
	$yr = getYear($publicationDate);
	
	//echo $mnth.", ".$yr;
	echo "<tr>\n
		<td><a href='viewPicture.php?id=$ComicID'>$Title</td>
		<td>$Volume</td>
		<td>$Issue</td>
		<td>$mnth, $yr</td>
		<td>$Notes</td>
		<td>$StoryTitle</td>
		<td>$Publisher</td>
		<td>$$PricePaid</td>
		<td>$$Value</td>
		<td>$Condition</td>
		</tr>\n";
}
echo "</table>\n";
echo "<a href='menu.php'>Back to main menu</a> <br>";
echo "<a href='logout.php'>Logout</a> <br><br>";
echo "<a href='ExportList.php'>Download List</a> <br>";
echo "</body></html>";
?>