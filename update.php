<?php
include 'utilities.php';
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$CID=$_GET['id'];
$selectComic="SELECT Comics.*, ComicAlias.Title as altTitle, ComicAlias.Volume as altVolume, ComicAlias.Issue as altIssue 
FROM Comics left join ComicAlias on Comics.ComicID=ComicAlias.ComicID WHERE Comics.ComicID='$CID'";
$result=mysqli_query($cxn,$selectComic) or die ("Cannot search for comic with ID=$CID.<br>SQL ERROR: ".mysqli_error($cxn)."<br>".$selectComic);
$row=mysqli_fetch_assoc($result);

$allCreators="SELECT CreatorID, Creator FROM Creators
UNION
SELECT CreatorID, Alias FROM CreatorAlias ORDER BY 2";
$allCreatorsResult=mysqli_query($cxn,$allCreators);

/*$allArtists="SELECT CreatorID, Creator FROM Creators
UNION
SELECT ArtistID, Alias FROM ArtistAlias ORDER BY 2";
$allArtistsResult=mysqli_query($cxn,$allArtists);*/

$allCharacters="SELECT * FROM Characters ORDER BY Characters";
$allCharactersResult=mysqli_query($cxn,$allCharacters);

$allAlias="SELECT Characters.CharacterID, Characters.Characters, CharacterAliases.Alias
FROM Characters INNER JOIN CharacterAliases ON Characters.CharacterID= CharacterAliases.CharacterID ";
$allAliasResult=mysqli_query($cxn,$allAlias);
extract($row);

$mnthNum=getMonthNum(getMonth($publicationDate));
$mnth=getMonth($publicationDate);
?>
<html>
<head><title><?php echo $Title." Volume ".$Volume." "." #".$Issue ?></title></head>
<body bgcolor="#408080" text="#FFFFFF">
<form action='updateComic.php' method='GET'>
<table Border='1'>
<tr>
<td>ComicID: </td>
<td><input type="number" name="comicID" value="<?php echo "$CID"; ?>" readonly/></td>
</tr>
<tr>
<td>Title:</td>
<td><input type="text" size="40" name="title" step=".01" value="<?php echo "$Title"; ?>" /></td>
</tr>
<tr>
<td>Issue:</td>
<td><input type="number" size="5" name="issue" step=".01" value="<?php echo "$Issue"; ?>" /></td>
</tr>
<tr>
<td>Volume:</td>
<td><input type="number" size="5" name="volume" value="<?php echo "$Volume"; ?>" /></td>
</tr>
<td>Date:</td>
<td><select name ='month'>
<?php
echo "<option value='$mnthNum'>$mnth</option>";
echo "<option value='-01-01'>January</option>";
echo "<option value='-02-01'>February</option>";
echo "<option value='-03-01'>March</option>";
echo "<option value='-04-01'>April</option>";
echo "<option value='-05-01'>May</option>";
echo "<option value='-06-01'>June</option>";
echo "<option value='-07-01'>July</option>";
echo "<option value='-08-01'>August</option>";
echo "<option value='-09-01'>September</option>";
echo "<option value='-10-01'>October</option>";
echo "<option value='-11-01'>November</option>";
echo "<option value='-12-01'>December</option>";
echo "<option value='-03-20'>Spring</option>";
echo "<option value='-06-21'>Summer</option>";
echo "<option value='-09-22'>Fall</option>";
echo "<option value='-12-23'>Winter</option>";
echo "<option value='-12-30'>Annual</option>";
echo "<option value='-01-31'>Original Graphic Novel</option>";
?>
</select><input type="text" size="5" name="year" value="<?php echo getYear($publicationDate); ?>" /></td>
</tr>
<td>Story Title:</td>
<td><input type="text" size="40" name="storyTitle" value="<?php echo "$StoryTitle"; ?>" /></td>
</tr>
<td>Publisher:</td>
<td><select name ='publisher'>
<?php
	$sql="SELECT DISTINCT `Publisher` FROM `Publisher` ORDER BY `Publisher`";
	$result=mysqli_query($cxn,$sql);
echo "<option value=\"$Publisher\">$Publisher</option>\n";
while($row=mysqli_fetch_assoc($result))
{
	extract($row);
	echo "<option value=\"$Publisher\">$Publisher</option>\n";
}
?>
</select></td>
</tr>
<td>Price Paid:</td>
<td><input type="number" name="paid" step=".01" value="<?php echo "$PricePaid"; ?>" /></td>
</tr>
<td>Value:</td>
<td><input type="number" name="value" step=".01" value="<?php echo "$Value"; ?>" /></td>
</tr>
<td>Condition:</td>
<td><select name ='condition'>
<?php
	$sql="SELECT `Condition` FROM `Condition` ORDER BY `ConditionID`";
	$result=mysqli_query($cxn,$sql);
	echo "<option value='$Condition'>$Condition</option>\n";
	while($row=mysqli_fetch_assoc($result))
	{
	extract($row);
	echo "<option value='$Condition'>$Condition</option>\n";
}
?>
</select></td>
</tr>
<td>Notes (optional):</td>
<td><input type="text" size="40" name="notes"/></td>
</tr>
<?php
	$notesSQL="SELECT * FROM Notes WHERE ComicID=$CID";
	$notesResults=mysqli_query($cxn,$notesSQL)or die("Could not execute Notes Search. ".mysqli_error($cxn));
	$count = 1;
	while($notesRow=mysqli_fetch_assoc($notesResults))
	{
		extract($notesRow);
		echo "<td>Note #$count:</td>";
		echo "<td><input type='checkbox' name='noteIDs[]' value='$NoteID' /> Delete $Notes</td></tr>";
		$count++;
	}
?>
<tr>
<td>Alternate Title:</td>
<td><input type="text" size="40" name="alternateTitle" value="<?php echo "$altTitle"; ?>" /></td>
</tr>
<tr>
<td>Alternate Volume:</td>
<td><input type="number" size="5" name="alternateVolume" value="<?php echo "$altVolume"; ?>" /></td>
</tr>
<tr>
<td>Alternate Issue:</td>
<td><input type="number" size="5" name="alternateIssue" value="<?php echo "$altIssue"; ?>" /></td>
</tr>
<td>Picture:</td>
<td><input type="text" size="40" style=<?php if(empty($Picture)) echo "\"color:red\""; else echo "\"color:black\"";?> name="picture" value="<?php
	$oldPic = $Picture;
	if(empty($Picture)){
		$url = getURL($Title,$Volume,$Issue,$Notes);
		echo "$url";
	} else {echo "$Picture";}
?>" /></td>

</tr>
<td>Comic entered on:</td>
<td><?php 
	if(empty($RecordCreationDate))
		echo "No date available";
	else
		echo $RecordCreationDate; ?></td>
</tr>

</table> 
<br>
<input type="hidden" size="30" name="oldPicture" value="<?php echo "$Picture";?>" />
Writer(s):<br>
<select name ='Writer'>
<option value=''></option>
<?php
	while($allWritersRow=mysqli_fetch_assoc($allCreatorsResult))
	{
		extract($allWritersRow);
		echo "<option value='$CreatorID'>$Creator</option>\n";
	}
?>
</select>
Or enter a new writer:
<input type="text" size="30" name="alternateWriter"/>
<?php echo '<img STYLE="position:absolute; TOP:7px; LEFT:550px;" src="'.$Picture.'" ALT="Picture unavailable">' ?>
<?php
$writerSQL="SELECT ComicWriters.ComicID, Creators.Creator, Creators.CreatorID
		FROM Creators
		INNER JOIN (
		Comics
		INNER JOIN ComicWriters ON Comics.ComicID = ComicWriters.ComicID
		) ON Creators.CreatorID = ComicWriters.WriterID WHERE ComicWriters.ComicID='$ComicID' ORDER BY Creators.Creator";
		
		$writerResult=mysqli_query($cxn,$writerSQL)or die("Could not execute Writer Search. ".mysqli_error($cxn));
		echo "<table style='display: inline'>" ;
		echo "<tr><td colspan='1'><hr /></td></tr><br>";
		while($writerRow=mysqli_fetch_assoc($writerResult))
		{
			extract($writerRow);
			echo "<td>$Creator</td>";
			echo "<td><input type='checkbox' name='writerFields[]' value='$CreatorID' /> Delete $Creator</td></tr>";
		}
		echo "</table>\n";
?>
<br>
<br>
Artist(s):<br>
<select name ='Artist'>
<option value=''></option>
<?php
	mysqli_data_seek($allCreatorsResult, 0); //Since this is the same query as the writers drop down, it needs to be set back to the beginning.
	while($allArtistsRow=mysqli_fetch_assoc($allCreatorsResult))
	{
		extract($allArtistsRow);
		echo "<option value='$CreatorID'>$Creator</option>\n";
	}
?>
</select>
Or enter a new artist:
<input type="text" size="30" name="alternateArtist"/>
<?php
$artistSQL="SELECT ComicArtists.ComicID, Creators.Creator, Creators.CreatorID
		FROM Creators
		INNER JOIN (
		Comics
		INNER JOIN ComicArtists ON Comics.ComicID = ComicArtists.ComicID
		) ON Creators.CreatorID = ComicArtists.ArtistID WHERE ComicArtists.ComicID='$ComicID' ORDER BY Creators.Creator";
		
		$artistResult=mysqli_query($cxn,$artistSQL)or die("Could not execute artist Search. ".mysqli_error($cxn));
		echo "<table style='display: inline'>" ;
		echo "<tr><td colspan='1'><hr /></td></tr><br>";
		while($artistRow=mysqli_fetch_assoc($artistResult))
		{
			extract($artistRow);
			echo "<td>$Creator</td>";
			echo "<td><input type='checkbox' name='artistFields[]' value='$CreatorID' /> Delete $Creator</td></tr>";
		}
		echo "</table>\n";
?>
<br>
<br>
Character(s):<br>
<select name ='Character'>
<option value=''></option>
<?php
	while($allCharactersRow=mysqli_fetch_assoc($allCharactersResult))
	{
		extract($allCharactersRow);
		echo "<option value='$CharacterID'>$Characters</option>\n";
	}
?>
</select><br>
<select name ='Alias'>
<option value=''></option>
<?php
	while($allAliasRow=mysqli_fetch_assoc($allAliasResult))
	{
		extract($allAliasRow);
		echo "<option value=\"$Alias\">$Alias ($Characters)</option>\n";
	}
?>
</select>
<br><br>
Or enter a new character:
<input type="text" size="30" name="alternateCharacter"/>
<input type="text" size="20" name="alternateAlias"/><br>
<?php
		$characterSQL="SELECT Characters.CharacterID, Characters.Characters, ComicCharacters.AppearsAs
		FROM Characters
		INNER JOIN (
		Comics
		INNER JOIN ComicCharacters ON Comics.ComicID = ComicCharacters.ComicID
		) ON Characters.CharacterID= ComicCharacters.CharacterID WHERE ComicCharacters.ComicID='$ComicID' ORDER BY Characters.Characters";
		
		$characterResult=mysqli_query($cxn,$characterSQL)or die("Could not execute Character Search");
		echo "<table style='display: inline'>\n" ;
		echo "<tr><td colspan='1'><hr /></td></tr><br>\n";
		while($characterRow=mysqli_fetch_assoc($characterResult))
		{
			extract($characterRow);
			echo "<td>$Characters</td>\n";
			$aliasSQL="SELECT Alias, CharacterAliases.CharacterID FROM CharacterAliases INNER JOIN Characters ON Characters.CharacterID=CharacterAliases.CharacterID WHERE Characters = \"$Characters\" ORDER BY Alias";
			$editAliasResult=mysqli_query($cxn,$aliasSQL)or die("Could not execute edit Alias Search");
			$numAliases=mysqli_num_rows($editAliasResult);
			if($numAliases!=0)
			{
				echo "<td><select name ='editAlias[]'>\n";
				echo "<option value=\"$AppearsAs $CharacterID\">$AppearsAs</option>\n";
				echo "<option value=\" $CharacterID\"></option>\n";
				while($editAliasRow=mysqli_fetch_assoc($editAliasResult))
				{
					extract($editAliasRow);
					echo "<option value=\"$Alias $CharacterID\">$Alias</option>\n";
				}
				echo "</td>\n";
			}
			else
			{
				echo "<td></td>\n";
			}
			echo "<td>$AppearsAs</td>\n";
			echo "<td><input type='checkbox' name='characterFields[]' value='$CharacterID' /> Delete $Characters</td></tr>\n";
		}
		echo "</table>\n";
?>
<br>
<br>

<td colspan='1'><hr />
<br>
<br>
<input type='submit' value='Update comic' /><br>
<a href="menu.php">Back to main menu</a> <br>
</body></html>
