<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or header("Location: index.php?login=false");
$writerSearch=$_GET['id'];
$page=$_GET['page'];
$writerPicSQL="SELECT * FROM Writers WHERE WriterID='$writerSearch'";
$picResult=mysqli_query($cxn,$writerPicSQL)or die("Could not search by writer");
$picRow=mysqli_fetch_assoc($picResult);
extract($picRow);

$sql="SELECT Writers.WriterID, Writers.Writer, Writers.writerPic, Comics.ComicID, Comics.Title, Comics.Issue, Comics.Volume, Comics.Notes, Comics.Month, Comics.Year,
Comics.PricePaid, Comics.Value, Comics.StoryTitle, Comics.Publisher, Comics.Condition, Comics.Picture
FROM Comics INNER JOIN (Writers INNER JOIN ComicWriters ON Writers.WriterID = ComicWriters.WriterID) ON Comics.ComicID = ComicWriters.ComicID
WHERE (((Writers.WriterID)='$writerSearch')) ORDER BY Comics.Title,Comics.Volume, Comics.Issue, Comics.Notes";

$result=mysqli_query($cxn,$sql)or die("Could not search by writer");
$numIssues=mysqli_num_rows($result);

echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
</body></html>";
echo "<h1>$Writer</h1>";
echo "$Writer has contributed to $numIssues issues.<br>";
echo "<a href='http://ryan-brannan.com/updateWriterInput.php?id=$WriterID'>
			<img src='$writerPic' ALT='Picture unavailable' BORDER='2'/></a><br><br>";
echo "<a href='http://ryan-brannan.com/menu.php'>Back to main menu</a> <br>";
echo "<a href='http://ryan-brannan.com/searchWriters.php'>Back to writer seach</a> <br>";
echo "<a href=\"http://ryan-brannan.com/logout.php\">Logout</a> <br>";

if(!(empty($page)))
	$sql=$sql." LIMIT $page, 100";
else
	$sql=$sql." LIMIT 0, 100";

$result=mysqli_query($cxn,$sql)or die("Could not search by writer");
$numPages = ceil($numIssues/100);
if($numPages > 1)
{
	echo "Page: ";
	for($i = 0; $i < $numPages; $i++)
	{
		$page = 100 * $i;
		$pageNum = $i + 1;
		echo("<a href='http://ryan-brannan.com/viewWriter.php?id=$writerSearch&page=$page'>$pageNum</a>");
		if($i != ($numPages - 1))
			echo ", ";
	}
}
echo "<tr><td colspan='1'><hr /></td></tr><br>";
displayComics($cxn,$result);
?>