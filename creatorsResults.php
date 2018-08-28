<?php
session_cache_limiter('private_no_expire');
session_start();
ini_set('session.cache_limiter', 'private');
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$creatorSearch=$_POST['searchCreator'];
//$sql="SELECT * FROM Artists WHERE Artist LIKE '%$artistSearch%' ORDER BY Artist";
$sql="SELECT CreatorID, Creator FROM Creators WHERE Creator LIKE '%$creatorSearch%' UNION SELECT CreatorID, Alias FROM CreatorAlias WHERE Alias LIKE '%$creatorSearch%' ORDER BY 2";
$result=mysqli_query($cxn,$sql);
$numCreators=mysqli_num_rows($result);

echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
<h1>Creator Results</h1><br>
<a href='menu.php'>Back to main menu</a>
<br>
<br>
</body></html>";
echo "Your search matched $numCreators comic creators.";
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	echo "<table>" ;
	
	echo "<td><a href=viewCreator.php?id=$CreatorID>$Creator</a></td>";
	echo "<tr><td colspan='1'><hr /></td></tr>";
}
?>