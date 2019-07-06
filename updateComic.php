<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or die ("Could not connect");
$CID=$_GET['comicID'];
$Title=$_GET['title'];
$Issue=$_GET['issue'];
$Volume=$_GET['volume'];
$Month=$_GET['month'];
$Year=$_GET['year'];
$StoryTitle=$_GET['storyTitle'];
$Publisher=$_GET['publisher'];
$Paid=$_GET['paid'];
$Value=$_GET['value'];
$Condition=$_GET['condition'];
$Notes=$_GET['notes'];
$Picture=$_GET['picture'];
$oldPic=$_GET['oldPicture'];
$WriterID=$_GET['Writer'];
$ArtistID=$_GET['Artist'];
$fields = $_GET['writerFields'];
$artistFields=$_GET['artistFields'];
$noteIDs = $_GET['noteIDs'];
$altWriter=trim($_GET['alternateWriter']);
$altArtist=trim($_GET['alternateArtist']);
$characterID=$_GET['Character'];
$alias=$_GET['Alias'];
$charFields=$_GET['characterFields'];
$altCharacter=trim($_GET['alternateCharacter']);
$altAlias=trim($_GET['alternateAlias']);
$editAliases=$_GET['editAlias'];
$altTitle=$_GET['alternateTitle'];
$altVolume=$_GET['alternateVolume'];
$altIssue=$_GET['alternateIssue'];

for ($j=0;$j<count($noteIDs);$j++) 
{
	$deleteNotesSQL="DELETE FROM Notes WHERE ComicID='$CID' AND NoteID='$noteIDs[$j]'";
	mysqli_query($cxn,$deleteNotesSQL)or die("Could not delete notes");
}

for ($i=0;$i<count($fields);$i++) 
{
	$deleteWriterSQL="DELETE FROM ComicWriters WHERE ComicID='$CID' AND WriterID='$fields[$i]'";
	mysqli_query($cxn,$deleteWriterSQL)or die("Could not delete writer");
}

for ($j=0;$j<count($artistFields);$j++) 
{
	$deleteArtistSQL="DELETE FROM ComicArtists WHERE ComicID='$CID' AND ArtistID='$artistFields[$j]'";
	mysqli_query($cxn,$deleteArtistSQL)or die("Could not delete artist");
}

for ($k=0;$k<count($charFields);$k++) 
{
	$deleteCharacterSQL="DELETE FROM ComicCharacters WHERE ComicID='$CID' AND CharacterID='$charFields[$k]'";
	mysqli_query($cxn,$deleteCharacterSQL)or die("Could not delete character");
}

for ($l=0;$l<count($editAliases);$l++) 
{
	$string = $editAliases[$l];
	$tok = strtok($string, " ");
	while ($tok !== false) 
	{
		$prev=$tok;
		$tok = strtok(" \n\t");
	}
	$aka=str_replace(" $prev","",$editAliases[$l]);
	$updateAliasSQL="UPDATE ComicCharacters SET AppearsAs=\"$aka\" WHERE ComicCharacters.ComicID='$CID' AND CharacterID='$prev'";
	
	mysqli_query($cxn,$updateAliasSQL)or die("Could not update alias");
}

if(!(empty($altWriter)))
{
	$creatorCount="SELECT MAX(CreatorID) as maxID FROM Creators";
	$result=mysqli_query($cxn,$creatorCount)or die("Could not count creators. ".mysqli_error($cxn));
	$row=mysqli_fetch_assoc($result);
	extract($row);
	$maxID++;
	$insertCreator="INSERT INTO Creators (CreatorID, Creator) VALUES ('$maxID', '$altWriter')";
	$WriterID=$maxID;
	mysqli_query($cxn,$insertCreator)or die("Could not insert into creators: ".mysqli_error($cxn));
}

if(!(empty($altArtist)))
{
	$creatorCount="SELECT MAX(CreatorID) as maxID FROM Creators";
	$result=mysqli_query($cxn,$creatorCount)or die("Could not count creators. ".mysqli_error($cxn));
	$row=mysqli_fetch_assoc($result);
	extract($row);
	$maxID++;
	$insertCreator="INSERT INTO Creators (CreatorID, Creator) VALUES ('$maxID', '$altArtist')";
	$ArtistID=$maxID;
	mysqli_query($cxn,$insertCreator)or die("Could not insert into creators: ".mysqli_error($cxn));
}

if(!(empty($altCharacter)))
{
	$characterCount="SELECT COUNT(CharacterID) as characterCount FROM Characters";
	$result=mysqli_query($cxn,$characterCount)or die("Could not count characters");
	$row=mysqli_fetch_assoc($result);
	extract($row);
	$characterCount++;
	$insertCharacter="INSERT INTO Characters (CharacterID, Characters) VALUES ('$characterCount', \"$altCharacter\")";
	$characterID=$characterCount;
	mysqli_query($cxn,$insertCharacter)or die("Could not insert into character: ".mysqli_error($cxn));
	
	if(!(empty($altAlias)))
	{
		$insertAlias="INSERT INTO CharacterAliases (CharacterID, Alias) VALUES ('$characterID', \"$altAlias\")";
		$alias=$altAlias;
		mysqli_query($cxn,$insertAlias)or die("Could not insert into alias");
	}
}

if(!(empty($characterID)))
{
	$addCharacterSQL="INSERT INTO ComicCharacters (ComicID, CharacterID, AppearsAs) VALUES (\"$CID\", \"$characterID\", \"$alias\")";
	mysqli_query($cxn,$addCharacterSQL)or die("Could not insert into ComicCharacters $addCharacterSQL");
}

$updatePicture = substr($Picture, 3);
$updatePicture = $_SERVER['DOCUMENT_ROOT']."/".$updatePicture; //gives full path.

if(empty($oldPic) && !file_exists($updatePicture))
{
	$Picture='';
}

$updateSQL="UPDATE Comics SET Comics.Title=\"$Title\", Comics.Issue='$Issue', Comics.Volume='$Volume', 
Comics.publicationDate='".$Year.$Month."', Comics.StoryTitle=\"$StoryTitle\", Comics.Publisher=\"$Publisher\", Comics.PricePaid='$Paid', Comics.Value='$Value', 
Comics.Condition='$Condition', Comics.Picture='$Picture' WHERE ComicID='$CID'";

if((strcmp($oldPic, $Picture) != 0) && !empty($oldPic) && !empty($Picture))
{
	$oldPic = substr($oldPic, 3);
	$oldPic = $_SERVER['DOCUMENT_ROOT']."/".$oldPic; //gives full path.
	
	$Picture = substr($Picture, 3);
	$Picture = $_SERVER['DOCUMENT_ROOT']."/".$Picture; //gives full path.

	$oldFileExists = file_exists($oldPic);
	$newFileExists = file_exists($Picture);
	if($oldFileExists && !$newFileExists)
		rename($oldPic, $Picture);
}

if($WriterID>0)
{
	$addWriterSQL="INSERT INTO ComicWriters (ComicID,WriterID) VALUES ('$CID','$WriterID')";
	//echo $addWriterSQL;
	mysqli_query($cxn,$addWriterSQL)or die("Could not update writer");
}
if($ArtistID>0)
{
	$addArtistSQL="INSERT INTO ComicArtists (ComicID,ArtistID) VALUES ('$CID','$ArtistID')";
	mysqli_query($cxn,$addArtistSQL)or die("Could not update artists");
}

mysqli_query($cxn,$updateSQL)or die("Could not update<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$updateSQL."\n".mysqli_error());
logEvent($cxn, "Update issue. ID=$CID, title=$Title, issueNum=$Issue, issueVol=$Volume.");

if($Notes != Null)
{
	$insertNotesSQL = "INSERT INTO Notes (ComicID, Notes) VALUES ($CID, \"$Notes\")";
	mysqli_query($cxn,$insertNotesSQL)or die("Could not insert into notes<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$insertNotesSQL."\n".mysqli_error());
	logEvent($cxn, "Create note. ID=$CID, title=$Title, issueNum=$Issue, issueVol=$Volume note=$Notes.");
}

if(!empty($altTitle) && !empty($altVolume) && !empty($altIssue))
{
	$comicAliasSQL="INSERT INTO ComicAlias (ComicID, Title, Issue, Volume) VALUES ($CID, \"$altTitle\", $altIssue, $altVolume)
	on duplicate key UPDATE Title=VALUES(Title), Volume=VALUES(Volume), Issue=VALUES(Issue)";
	mysqli_query($cxn,$comicAliasSQL)or die("Could not add comic alias.<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$comicAliasSQL."\n".mysqli_error());
	logEvent($cxn, "Create comic alias. ID=$CID, aliasTitle=$altTitle, aliasIssueNum=$altIssue, aliasIssueVol=$altVolume .");
}


header("Location: update.php?id=$CID");
?>