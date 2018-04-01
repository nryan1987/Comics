<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$artistSearch=$_POST['searchArtist'];
$sql="SELECT * FROM Artists WHERE Artist LIKE '%$artistSearch%' ORDER BY Artist";
$result=mysqli_query($cxn,$sql);

echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
<h1>Artists Results</h1><br>
<a href='http://ryan-brannan.com/menu.php'>Back to main menu</a>
<br>
<br>
</body></html>";
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	echo "<table>" ;
	
	echo "<td><a href=http://ryan-brannan.com/viewArtist.php?id=$ArtistID>$Artist</a></td></tr>";
	echo "<tr><td colspan='1'><hr /></td></tr>";
}
?>