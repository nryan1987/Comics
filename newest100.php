<?php
include 'utilities.php';
echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
</body></html>";
echo "<h1>Latest 100 issues</h1>";
echo "<a href='menu.php'>Back to main menu</a> <br>";

session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"Comics") or header("Location: index.php?login=false");
$sql="SELECT * FROM Comics.Comics ORDER BY ComicID DESC LIMIT 100";
$result=mysqli_query($cxn,$sql);

displayComics($cxn,$result);
?>
<a href='menu.php'>Back to main menu</a> <br>
<a href='logout.php'>Logout</a> <br>
</body></html>