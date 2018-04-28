<?php
include 'utilities.php';
session_start();
$cxn=@mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"Comics") or header("Location: index.php?login=false");
$charSearch=$_GET['id'];
$aliasSearch=$_GET['alias'];
$page=$_GET['page'];
$characterPicSQL="SELECT * FROM Characters WHERE CharacterID='$charSearch'";
$picResult=mysqli_query($cxn,$characterPicSQL)or die("Could not search by character");
$picRow=mysqli_fetch_assoc($picResult);
extract($picRow);

echo "<html>
<body bgcolor=\"#408080\" text=\"#FFFFFF\">
</body></html>";
if(empty($aliasSearch))
{
	$sql="SELECT Characters.CharacterID, Characters.Characters, Characters.CharacterPic, Comics.ComicID, Comics.Title, Comics.Issue, Comics.Volume, Comics.Month, Comics.Year,
	Comics.publicationDate, Comics.PricePaid, Comics.Value, Comics.StoryTitle, Comics.Publisher, Comics.Condition, Comics.Picture
	FROM Comics INNER JOIN (Characters INNER JOIN ComicCharacters ON Characters.CharacterID = ComicCharacters.CharacterID) ON Comics.ComicID = ComicCharacters.ComicID
	WHERE (((Characters.CharacterID)='$charSearch')) ORDER BY Comics.Title,Comics.Volume, Comics.Issue";
	echo "<html><head><title>$Characters</title></head></html>";
	echo "<h1>$Characters</h1>";
}
else
{
	$sql="SELECT Characters.CharacterID, Characters.Characters, ComicCharacters.AppearsAs, Characters.CharacterPic, Comics.ComicID, Comics.Title, Comics.Issue, Comics.Volume, 
	Comics.Month, Comics.Year, Comics.publicationDate, Comics.PricePaid, Comics.Value, Comics.StoryTitle, Comics.Publisher, Comics.Condition, Comics.Picture 
	FROM Comics INNER JOIN (Characters INNER JOIN ComicCharacters ON Characters.CharacterID = ComicCharacters.CharacterID) ON Comics.ComicID = ComicCharacters.ComicID
	WHERE (((Characters.CharacterID)='$charSearch')AND(ComicCharacters.AppearsAs='$aliasSearch')) ORDER BY Comics.Title,Comics.Volume, Comics.Issue";
	echo "<html><head><title>$aliasSearch ($Characters)</title></head></html>";
	echo "<h1>$aliasSearch ($Characters)</h1>";
}

$result=mysqli_query($cxn,$sql)or die("Could not search by character");
$numIssues=mysqli_num_rows($result);
if($numIssues==1)
{
	echo "Appears in $numIssues issue.<br>";
}
else
{
	echo "Appears in $numIssues issues.<br>";
}
if(!(empty($page)))
	$sql=$sql." LIMIT $page, 100";
else
	$sql=$sql." LIMIT 0, 100";

$result=mysqli_query($cxn,$sql)or die("Could not search by character");
$aliasSQL="SELECT Alias FROM CharacterAliases WHERE CharacterID='$charSearch'";
$aliasResult=mysqli_query($cxn,$aliasSQL)or die("Could not search for alias");

if(mysqli_num_rows($aliasResult)>0)
{
	echo "Also Known As:<br>";
	while($aliasRow=mysqli_fetch_assoc($aliasResult))
	{
		extract($aliasRow);
		$aka=urlencode($Alias);
		echo "<a href=viewCharacter.php?id=$CharacterID&alias=$aka>$Alias</a><br>";
	}
}

echo "<a href='updateCharacterInput.php?id=$CharacterID'>
			<img src='$CharacterPic' ALT='Picture unavailable' BORDER='2'/></a><br><br>";
echo "<a href='menu.php'>Back to main menu</a> <br>";
echo "<a href='searchCharacters.php'>Back to character seach</a> <br>";
echo "<a href=\"logout.php\">Logout</a> <br> <br>";
$numPages = ceil($numIssues/100);
if($numPages > 1)
{
	echo "Page: ";
	for($i = 0; $i < $numPages; $i++)
	{
		$page = 100 * $i;
		$pageNum = $i + 1;
		echo("<a href='viewCharacter.php?id=$charSearch&page=$page'>$pageNum</a>");
		if($i != ($numPages - 1))
			echo ", ";
	}
}
echo "<tr><td colspan='1'><hr /></td></tr><br>";
displayComics($cxn,$result);
?>