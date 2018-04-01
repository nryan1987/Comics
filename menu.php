<?php
session_cache_limiter('private_no_expire');
session_start();
ini_set('session.cache_limiter', 'private');
if(($_SESSION['uname']=='')||($_SESSION['uname']=='myusername'))
{
	$_SESSION['uname']=$_POST['myusername'];
	$_SESSION['pswrd']=$_POST['mypassword'];

	//$insertLog="INSERT INTO ActivityLog (UserName, Password, Date) VALUES (".$_SESSION['uname'].", ".$_SESSION['pswrd'].", ".date("Y-m-d H:i:s").")";
	//mysqli_query($cxn,$insertLog)or die("Could not insert into ActivityLog");
	//echo $insertLog;
}
$cxn=@mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");
//$cxn=@mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die(mysqli_connect_error());
?>
<html>
<body bgcolor="#408080" text="#FFFFFF">
<head><title>COMICS</title></head>
<a href="newComic.php">Enter a new comic</a> <br>
<a href="viewAllComics.php">See All Comics</a> <br>
<a href="titles.php">See the most popular titles</a> <br>
<a href="publisher.php">See the most popular publishers</a> <br>
<a href="newest100.php">See the Last 100 issues entered</a> <br>
<a href="findMissingIssues.php">Find missing issues</a> <br>
<a href="search.php">Search Comics</a> <br>
<a href="searchCreators.php">Search Creators</a> <br>
<a href="searchCharacters.php">Search Characters</a> <br>
<a href="logout.php">Logout</a> <br>
</body>
</html>