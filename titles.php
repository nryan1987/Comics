<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");
$sql="SELECT Comics.Title, Count(Comics.Title) AS CountOfTitle FROM Comics GROUP BY Comics.Title, Comics.Title ORDER BY Count(Comics.Title) DESC , Comics.Title";
$result=mysqli_query($cxn,$sql);

$totalsSQL="SELECT Count(Comics.ComicID) AS CountOfComics FROM Comics";
$totalsResult=mysqli_query($cxn,$totalsSQL);
$totalsRow=mysqli_fetch_assoc($totalsResult);
extract($totalsRow);
echo "<html>
	  <body
	  <head><title>Popular Titles</title></head>
	  <body bgcolor=\"#408080\" text=\"#FFFFFF\">";
echo "<h1>Most Popular Titles</h1><br>";
echo "<a href='http://ryan-brannan.com/menu.php'>Back to main menu</a> <br>";
echo "<a href='http://ryan-brannan.com/logout.php'>Logout</a> <br>";
echo "<table style=\"color:white\">";
echo "<td>Title</td>";
echo "<td>Number of Issues</td>";
echo "<td align=right>Percent of total</td>";
echo "<tr><td colspan='3'><hr /></td></tr><br>";
$rowNumber=0;
$titleNum=0;
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	if($CountOfTitle!=$titleNum)
	{
		$titleNum=$CountOfTitle;
		$rowNumber++;
	}
	$percent = ($CountOfTitle/$CountOfComics) * 100;
	echo "<tr>\n
		<td>$rowNumber. $Title</td>
		<td align=right>$CountOfTitle</td>
		<td align=right>$percent %</td>
		</tr>\n";
		
}
echo "</table\n";
?>
</body></html>