<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$sql="SELECT Publisher.Publisher, Count(Comics.Title) AS CountOfTitle
FROM Publisher INNER JOIN Comics ON Publisher.Publisher = Comics.Publisher
GROUP BY Publisher.Publisher ORDER BY CountOfTitle DESC, Publisher";
$result=mysqli_query($cxn,$sql);
echo "<html>
	  <body
	  <head><title>Popular Publishers</title></head>
	  <body bgcolor=\"#408080\" text=\"#FFFFFF\">";
echo "<h1>Most Popular Publishers</h1><br>";
echo "<a href='http://ryan-brannan.com/menu.php'>Back to main menu</a> <br>";
echo "<a href='http://ryan-brannan.com/logout.php'>Logout</a> <br>";
echo "<table style=\"color:white\">";
echo "<td>Publisher</td>";
echo "<td>Number of Issues</td>";
echo "<tr><td colspan='2'><hr /></td></tr><br>";
$rowNumber=1;
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	echo "<tr>\n
		<td>$rowNumber. $Publisher</td>
		<td align=right>$CountOfTitle</td>
		</tr>\n";
		$rowNumber++;
}
echo "</table\n";
?>
</body></html>