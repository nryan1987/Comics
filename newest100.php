<?php
include 'utilities.php';
session_cache_limiter('private_no_expire');
session_start();
ini_set('session.cache_limiter', 'private');
@$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");

echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
</body></html>";
echo "<h1>Latest 100 issues</h1>";

//$sql="SELECT * FROM Comics.Comics ORDER BY ComicID DESC LIMIT 100";
$sql="SELECT * FROM Comics ORDER BY ComicID DESC LIMIT 100";
$result=mysqli_query($cxn,$sql);/* or die("Could not execute Search: $sql");*/

displayComics($cxn,$result);

echo "<a href='menu.php'>Back to main menu</a> <br>";
echo "<a href='logout.php'>Logout</a> <br>";

?>
</body></html>