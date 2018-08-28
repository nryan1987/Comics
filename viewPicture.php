<?php
include 'utilities.php';
session_start();
$cxn=@mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$CID=$_GET['id'];

$findComicSQL="SELECT * FROM Comics WHERE ComicID='$CID'";
$result=mysqli_query($cxn,$findComicSQL)or die("Could not search Comics.");
$row=mysqli_fetch_assoc($result);
extract($row);

$mnth = getMonth($publicationDate);
$yr = getYear($publicationDate);
?>
<html>
<head><title><?php echo $Title." Volume ".$Volume." "." #".$Issue ?></title><link rel="shortcut icon" href=""></head>

<body bgcolor="#408080" text="#FFFFFF">

<table Border='1'>
<tr>
<td>ComicID: </td>
<td><?php echo "$CID" ?></td>
</tr>
<tr>
<td>Title:</td>
<td><?php echo "$Title" ?></td>
</tr>
<tr>
<td>Issue:</td>
<td><?php echo "$Issue" ?></td>
</tr>
<tr>
<td>Volume:</td>
<td><?php echo "$Volume" ?></td>
</tr>
<td>Date:</td>
<td><?php echo "$mnth, $yr" ?></td>
</tr>
<td>Story Title:</td>
<td><?php echo "$StoryTitle" ?></td>
</tr>
<td>Publisher:</td>
<td><?php echo "$Publisher" ?></td>
</tr>
<td>Price Paid:</td>
<td><?php echo "$$PricePaid" ?></td>
</tr>
<td>Value:</td>
<td><?php echo "$$Value" ?></td>
</tr>
<td>Condition:</td>
<td><?php echo "$Condition" ?></td>
</tr>

<?php
	$notesSQL="SELECT * FROM Notes WHERE ComicID=$CID";
		
	$notesResults=mysqli_query($cxn,$notesSQL)or die("Could not execute Notes Search. ".mysqli_error($cxn));
	$count = 1;
	while($notesRow=mysqli_fetch_assoc($notesResults))
	{
		extract($notesRow);
		echo "<td>Note #$count:</td>";
		echo "<td>$Notes</td></tr>";
		$count++;
	}
?>

<tr>
<td colspan="2"><a href=update.php?id=<?php echo "$CID" ?>>Update <?php echo "$Title #$Issue" ?></td>
</tr>
</table> 
<?php echo '<img STYLE="position:absolute; TOP:7px; LEFT:525px;" src="'.$Picture.'" ALT="Picture unavailable">' ?>

<?php
		/*$listSQL="SELECT ComicID FROM Comics WHERE Title='$Title' ORDER BY Title, Volume, Issue, Notes";
		$listResult=mysqli_query($cxn,$listSQL)or die("Could not create list");
		$numIssues=mysqli_num_rows($listResult);
		//$issueArray[]=array;
		$j=0;
		while ($list=mysqli_fetch_array($listResult, MYSQL_NUM))
		{
			$issueArray[$j]=$list[0]; 
			$j++;
			
		}
		        $lastIsh=prev($issueArray);
				$nextIsh=next($issueArray);
				$currentIsh=current($issueArray);
				echo "ID is $currentIsh\n";
				echo "prev is $lastIsh\n";
				echo "next is $nextIsh\n";*/
		
		
		
		
		/*$writerSQL="SELECT ComicWriters.ComicID, Writers.WriterID, Writers.Writer
		FROM Writers
		INNER JOIN (
		Comics
		INNER JOIN ComicWriters ON Comics.ComicID = ComicWriters.ComicID
		) ON Writers.WriterID = ComicWriters.WriterID WHERE ComicWriters.ComicID='$ComicID' ORDER BY Writers.Writer";*/
		$writerSQL="SELECT ComicWriters.ComicID, Creators.CreatorID, Creators.Creator
		FROM Creators
		INNER JOIN (
		Comics
		INNER JOIN ComicWriters ON Comics.ComicID = ComicWriters.ComicID
		) ON Creators.CreatorID = ComicWriters.WriterID WHERE ComicWriters.ComicID='$ComicID' ORDER BY Creators.Creator";
		
		$writerResult=mysqli_query($cxn,$writerSQL)or die("Could not execute Writer Search");
		echo "<table style='display: inline'>" ;
		echo "<td>Writer(s)</td></tr>";
		echo "<tr><td colspan='1'><hr /></td></tr><br>";
		while($writerRow=mysqli_fetch_assoc($writerResult))
		{
			extract($writerRow);
			echo "<td><a href='viewCreator.php?id=$CreatorID&role=w'>$Creator</a></td></tr>";
		}
		echo "<tr><td colspan='2'><hr /></td></tr><br>";
		echo "</table>\n";
		
		$artistSQL="SELECT ComicArtists.ComicID,  Creators.CreatorID, Creators.Creator
		FROM Creators
		INNER JOIN (
		Comics
		INNER JOIN ComicArtists ON Comics.ComicID = ComicArtists.ComicID
		) ON Creators.CreatorID = ComicArtists.ArtistID WHERE ComicArtists.ComicID='$ComicID' ORDER BY Creators.Creator";
		
		$artistResult=mysqli_query($cxn,$artistSQL)or die("Could not execute Artist Search");
		
		echo "<table style='display: inline'>" ;
		echo "<td>Artist(s)</td></tr>";
		echo "<tr><td colspan='1'><hr /></td></tr><br>";
		while($artistRow=mysqli_fetch_assoc($artistResult))
		{
			extract($artistRow);
			echo "<td><a href='viewCreator.php?id=$CreatorID&role=a'>$Creator</a></td></tr>";
		}
		echo "<tr><td colspan='2'><hr /></td></tr><br>";
		echo "</table>\n";

		$characterSQL="SELECT Characters.CharacterID, Characters.Characters, Characters.CharacterPic, ComicCharacters.AppearsAs
		FROM Characters
		INNER JOIN (
		Comics
		INNER JOIN ComicCharacters ON Comics.ComicID = ComicCharacters.ComicID
		) ON Characters.CharacterID= ComicCharacters.CharacterID WHERE ComicCharacters.ComicID='$ComicID' ORDER BY Characters.Characters";
		
		$characterResult=mysqli_query($cxn,$characterSQL)or die("Could not execute Character Search");
		$numCharacters=mysqli_num_rows($characterResult);

		echo "<br><br><br><br><br><br><br><br><table style='display: inline'>" ;
		if($numCharacters == 1)
		{
			echo "$numCharacters Character appear in this issue.</td>";
		}
		else
		{
			echo "$numCharacters Characters appears in this issue.</td>";
		}
		echo "<tr><td colspan='1'><hr /></td></tr><br>";
		$count=1;
		while($characterRow=mysqli_fetch_assoc($characterResult))
		{
			extract($characterRow);
			echo "<td><a href='viewCharacter.php?id=$CharacterID'>
				<img src='$CharacterPic' ALT='Picture unavailable' BORDER='2' width='75' height='150'/></a>";
			if(empty($AppearsAs))
			{
				echo "<td><a href='viewCharacter.php?id=$CharacterID'>$Characters</a></td>";
			}
			else
			{
				echo "<td><a href='viewCharacter.php?id=$CharacterID'>$AppearsAs</a></td>";
			}
			if($count%8==0)
				echo "</tr>";
			$count++;
		}
		echo "<tr><td colspan='2'><hr /></td></tr><br>";
		echo "</table>\n";
		echo "<br>";
		echo "<br>";
		echo "<br><tr><td colspan='2'><hr /></td></tr>";		
?>
<a href="menu.php">Back to main menu</a> <br>
</body></html>