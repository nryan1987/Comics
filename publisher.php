<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"Comics") or header("Location: index.php?login=false");
$sql="SELECT Publisher.Publisher, Count(Comics.Title) AS CountOfTitle
FROM Publisher INNER JOIN Comics ON Publisher.Publisher = Comics.Publisher
GROUP BY Publisher.Publisher ORDER BY CountOfTitle DESC, Publisher";
$result=mysqli_query($cxn,$sql);

$totalsSQL="SELECT Count(Comics.ComicID) AS CountOfComics FROM Comics";
$totalsResult=mysqli_query($cxn,$totalsSQL);
$totalsRow=mysqli_fetch_assoc($totalsResult);
extract($totalsRow);

echo "<html>
	  <body
	  <head><title>Popular Publishers</title></head>
	  <body bgcolor=\"#408080\" text=\"#FFFFFF\">";
echo "<h1>Most Popular Publishers</h1><br>";
echo "<a href='menu.php'>Back to main menu</a> <br>";
echo "<a href='logout.php'>Logout</a> <br>";
echo "<table style=\"color:white\">";
echo "<td>Publisher</td>";
echo "<td>Number of Issues</td>";
echo "<td align=right>Percent of total</td>";
echo "<tr><td colspan='3'><hr /></td></tr><br>";
$rowNumber=1;
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	$percent = ($CountOfTitle/$CountOfComics) * 100;
	echo "<tr>\n
		<td>$rowNumber. $Publisher</td>
		<td align=right>$CountOfTitle</td>
		<td align=right>$percent %</td>
		</tr>\n";
		$rowNumber++;
}
echo "</table\n";
?>
</body></html>