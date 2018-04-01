<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");
$writerSearch=$_POST['searchWriters'];
$sql="SELECT * FROM Writers WHERE Writer LIKE '%$writerSearch%' ORDER BY Writer";
$result=mysqli_query($cxn,$sql)or die("Could not search writers");
echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
<h1>Writers Results</h1><br>
<a href='http://ryan-brannan.com/menu.php'>Back to main menu</a> <br>
<a href='http://ryan-brannan.com/logout.php'>Logout</a> <br>
<br>
<br>
</body></html>";
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	echo "<table>" ;
	
	echo "<td><a href=http://ryan-brannan.com/viewWriter.php?id=$WriterID>$Writer</a></td>";
	echo "<tr><td colspan='1'><hr /></td></tr>";
}
?>