<?php
	function getURL($title,$issueVol,$issueNum,$notes)
	{
		$imgTitle=str_replace(".","",$title);
		$imgTitle=str_replace(":","",$imgTitle);
		$imgTitle=str_replace("/"," ",$imgTitle);
		$imgTitle=str_replace("\\"," ",$imgTitle);
		$imgTitle=str_replace("'","",$imgTitle);
		$imgTitle=str_replace(",","",$imgTitle);
		$imgTitle=str_replace(" ","_",$imgTitle);
		$notes=str_replace(" ","_",$notes);
		if(empty($notes)) {
			$url="../images/Comic Pictures/".$imgTitle."_".$issueVol."_".$issueNum.".GIF";
		}
		else {
			$url="../images/Comic Pictures/".$imgTitle."_".$issueVol."_".$issueNum."_".$notes.".GIF";
		}
		
		return substr($url, 0, 80); //Truncates at 80 chars.
	}
	
	function displayComics($cxn,$result)
	{
		while($row=mysqli_fetch_assoc($result))
		{
			extract($row);
			echo "<table style='float: left'>" ;
	
			echo "<tr>\n
				<td>Title:</td>
				<td>$Title</td></tr>
				<td>Volume:</td>
				<td>$Volume</td></tr>
				<td>Issue:</td>
				<td>$Issue</td></tr>
				<td>Date:</td>
				<td>$Month, $Year</td></tr>
				<td>Story Title:</td>
				<td>$StoryTitle</td></tr>
				<td>Publisher:</td>
				<td>$Publisher</td></tr>
				<td>Price Paid:</td>
				<td>$$PricePaid</td></tr>
				<td>Value:</td>
				<td>$$Value</td></tr>
				<td>Grade:</td>
				<td>$Condition</td></tr>";
//				<td>Notes:</td>
//				<td>$Notes</td></tr>
//				</tr><br>";

			$notesSQL="SELECT * FROM Notes WHERE ComicID=$ComicID";
			$notesResults=mysqli_query($cxn,$notesSQL)or die("Could not execute Notes Search. ".mysqli_error($cxn));
			$count = 1;
			while($notesRow=mysqli_fetch_assoc($notesResults))
			{
				extract($notesRow);
				echo "<td>Note #$count:</td>";
				echo "<td>$Notes</td></tr>";
				$count++;
			}
			
			echo "</table>\n";
					
			echo "<table style='display: inline'>" ;
			echo "<td><a href='viewPicture.php?id=$ComicID'>
				<img src='$Picture' ALT='Picture unavailable' BORDER='2' width='250' height='375'/></a></td></tr>";
			echo "<td><a href=update.php?id=$ComicID>Update $Title #$Issue </a></td></tr>";
			echo "</table>\n";
		
			$writerSQL="SELECT ComicWriters.ComicID, Creators.Creator
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
				echo "<td>$Creator</td></tr>";
			}
			echo "<tr><td colspan='1'><hr /></td></tr><br>";
			echo "</table>\n";
			$artistSQL="SELECT ComicArtists.ComicID, Creators.Creator
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
				echo "<td>$Creator</td></tr>";
			}
			echo "<tr><td colspan='2'><hr /></td></tr><br>";
			echo "</table>\n";
			echo "<br>";
			echo "<br>";
			echo "<br><tr><td colspan='2'><hr /></td></tr><br>";		
		}
	}
	
	function cleanString($title)
	{
		$imgTitle=str_replace(" ","_",$title);
		$imgTitle=str_replace("'","",$imgTitle);
		$imgTitle=str_replace(".","",$imgTitle);
		$imgTitle=str_replace("?","",$imgTitle);
		$imgTitle=str_replace("!","",$imgTitle);
		$imgTitle=str_replace(","," ",$imgTitle);
		$imgTitle=str_replace("/","_",$imgTitle);
		
		return $imgTitle;
	}
	
	function addNewPublisher($cxn,$publisher)
	{
		$publisherCount="SELECT COUNT(PublisherID) as publisherCount FROM Publisher";
		$result=mysqli_query($cxn,$publisherCount)or die("Could not count publishers");
		$row=mysqli_fetch_assoc($result);
		extract($row);
		$publisherCount=$publisherCount+1;
		$insertPublisher="INSERT INTO Publisher (PublisherID, Publisher) VALUES ('$publisherCount', '$publisher')";
		mysqli_query($cxn,$insertPublisher)or die("Could not insert into publisher");
	}
?>