<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$creatorSearch=$_GET['id'];
$role=$_GET['role'];
$creatorPicSQL="SELECT * FROM Creators WHERE CreatorID='$creatorSearch'";
$picResult=mysqli_query($cxn,$creatorPicSQL)or die("Could not search by creator. ".mysqli_error($cxn));
$picRow=mysqli_fetch_assoc($picResult);
extract($picRow);

echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
</body></html>";

$artistSql="SELECT Creators.CreatorID, Creators.Creator, Comics.ComicID, Comics.Title, Comics.Issue, Comics.Volume, Comics.Month, Comics.Year,
Comics.PricePaid, Comics.Value, Comics.StoryTitle, Comics.Publisher, Comics.Condition, Comics.Picture
FROM Comics INNER JOIN (Creators INNER JOIN ComicArtists ON Creators.CreatorID = ComicArtists.ArtistID) ON Comics.ComicID = ComicArtists.ComicID
WHERE (((Creators.CreatorID)='$creatorSearch')) ORDER BY Comics.Title,Comics.Volume, Comics.Issue";

$writerSql="SELECT Creators.CreatorID, Creators.Creator, Creators.creatorPic, Comics.ComicID, Comics.Title, Comics.Issue, Comics.Volume, Comics.Month, Comics.Year,
Comics.PricePaid, Comics.Value, Comics.StoryTitle, Comics.Publisher, Comics.Condition, Comics.Picture
FROM Comics INNER JOIN (Creators INNER JOIN ComicWriters ON Creators.CreatorID = ComicWriters.WriterID) ON Comics.ComicID = ComicWriters.ComicID
WHERE (((Creators.CreatorID)='$creatorSearch')) ORDER BY Comics.Title,Comics.Volume, Comics.Issue";

$bothSql="SELECT DISTINCT * FROM Comics 
WHERE Comics.ComicID IN (SELECT ComicID FROM ComicWriters WHERE ComicWriters.WriterID='$creatorSearch' UNION SELECT ComicID FROM ComicArtists WHERE ComicArtists.ArtistID='$creatorSearch')
ORDER BY Comics.Title,Comics.Volume, Comics.Issue";

if($role == a) //artist
{
	$sql=$artistSql;
	echo "<h1>$Creator (Artist)</h1>";
}
else if($role == w) //writer
{
	$sql=$writerSql;
	echo "<h1>$Creator (Writer)</h1>";
}
else //writer and artist
{
	$sql=$bothSql;
	echo "<h1>$Creator</h1>";
}

$result=mysqli_query($cxn,$sql)or die("Could not search by creator. ".mysqli_error($cxn));
$numIssues=mysqli_num_rows($result);

echo "$Creator has contributed to $numIssues issues.<br>";

$aliasSQL="SELECT Alias FROM CreatorAlias WHERE CreatorID='$creatorSearch'";
$aliasResult=mysqli_query($cxn,$aliasSQL)or die("Could not search for alias. ".mysqli_error($cxn));

if(mysqli_num_rows($aliasResult)>0)
{
	echo "<br>Also Credited As:<br>";
	while($aliasRow=mysqli_fetch_assoc($aliasResult))
	{
		extract($aliasRow);
		$aka=urlencode($Alias);
		echo "$Alias<br>";
	}
}

echo "<a href='updateCreatorInput.php?id=$CreatorID'>
			<img src='$creatorPic' ALT='Picture unavailable' BORDER='2'/></a><br>";
echo "<a href='viewCreator.php?id=$CreatorID&role=w'>View $Creator's work as a writer</a> <br>";
echo "<a href='viewCreator.php?id=$CreatorID&role=a'>View $Creator's work as an artist</a> <br>";
echo "<a href='viewCreator.php?id=$CreatorID'>View all of $Creator's work.</a> <br><br>";
echo "<a href='menu.php'>Back to main menu</a> <br>";
echo "<a href='searchCreators.php'>Back to creator seach</a> <br>";
echo "<tr><td colspan='1'><hr /></td></tr><br>";
displayComics($cxn,$result);
?>