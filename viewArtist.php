<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$artistSearch=$_GET['id'];
$artistPicSQL="SELECT * FROM Artists WHERE ArtistID='$artistSearch'";
$picResult=mysqli_query($cxn,$artistPicSQL)or die("Could not search by artist");
$picRow=mysqli_fetch_assoc($picResult);
extract($picRow);

$sql="SELECT Artists.ArtistID, Artists.Artist, Comics.ComicID, Comics.Title, Comics.Issue, Comics.Volume, Comics.Notes, Comics.Month, Comics.Year,
Comics.PricePaid, Comics.Value, Comics.StoryTitle, Comics.Publisher, Comics.Condition, Comics.Picture
FROM Comics INNER JOIN (Artists INNER JOIN ComicArtists ON Artists.ArtistID = ComicArtists.ArtistID) ON Comics.ComicID = ComicArtists.ComicID
WHERE (((Artists.ArtistID)='$artistSearch')) ORDER BY Comics.Title,Comics.Volume, Comics.Issue, Comics.Notes";
$result=mysqli_query($cxn,$sql)or die("Could not search by artist");
$numIssues=mysqli_num_rows($result);

echo "<html>
<head><title>COMICS</title></head>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
</body></html>";
echo "<h1>$Artist</h1>";
echo "$Artist has contributed to $numIssues issues.<br>";
echo "<a href='http://ryan-brannan.com/updateArtistInput.php?id=$ArtistID'>
			<img src='$artistPic' ALT='Picture unavailable' BORDER='2'/></a><br><br>";
echo "<a href='http://ryan-brannan.com/menu.php'>Back to main menu</a> <br>";
echo "<a href='http://ryan-brannan.com/searchArtists.php'>Back to artist seach</a> <br>";
echo "<tr><td colspan='1'><hr /></td></tr><br>";
displayComics($cxn,$result);
?>