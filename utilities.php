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
				<td><a href='searchResults.php?searchTitle=".urlencode(str_replace("'","%",$Title))."' target='_blank' >$Title</a></td></tr>
				<td>Volume:</td>
				<td>$Volume</td></tr>
				<td>Issue:</td>
				<td>$Issue</td></tr>
				<td>Date:</td>
				<td>".getMonth($publicationDate).", ".getYear($publicationDate)."</td></tr>
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
			
			$aliasSQL="select CONCAT(Title, \" VOL. \", Volume, \" #\", Issue) as aliasStr from ComicAlias where ComicID=$ComicID";
			$aliasResults=mysqli_query($cxn,$aliasSQL)or die("Could not execute alias search. ".mysqli_error($cxn));
			while($aliasRow=mysqli_fetch_assoc($aliasResults))
			{
				extract($aliasRow);
				echo "<td>Also known as: </td>";
				echo "<td>$aliasStr</td></tr>";
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
		
	function getCurrentVolume($cxn,$title)
	{
		$currentVolume="SELECT COALESCE((SELECT MAX(Volume) from Comics where Title=\"$title\"),1) as currentVolume";
		$result=mysqli_query($cxn,$currentVolume)or die("Could not get current volume");
		$row=mysqli_fetch_assoc($result);
		extract($row);
		
		return $currentVolume;
	}
	
	function echoPage($pageTitle)
	{
		echo "<html>
			<head><title>COMICS</title></head>

			<body bgcolor=\"#408080\" text=\"#FFFFFF\">
			<h1>".$pageTitle."</h1><br>
			<a href='menu.php'>Back to main menu</a> <br>
			<a href='search.php'>Back to search</a> <br>
			</body></html>";
	}
	
	function convertToDate($month, $year)
	{
		if(strcmp($month, "13")==0) //Spring
			$pubDate=DateTime::createFromFormat("Y-m-d", $year."-3-20");
		else if(strcmp($month, "14")==0) //Summer
			$pubDate=DateTime::createFromFormat("Y-m-d", $year."-6-21");
		else if(strcmp($month, "15")==0) //Fall
			$pubDate=DateTime::createFromFormat("Y-m-d", $year."-9-22");
		else if(strcmp($month, "16")==0) //Winter
			$pubDate=DateTime::createFromFormat("Y-m-d", $year."-12-23");
		else if(strcmp($month, "17")==0) //Annual
			$pubDate=DateTime::createFromFormat("Y-m-d", $year."-12-30");
		else if(strcmp($month, "18")==0) //OGN
			$pubDate=DateTime::createFromFormat("Y-m-d", $year."-1-31");
		else
			$pubDate=DateTime::createFromFormat("Y-m-d", $year."-".$month."-1");
		
		return $pubDate;
	}
	
	function getMonth($pubDate)
	{
		$date = DateTime::createFromFormat('Y-m-d', $pubDate);
		//echo $date->format('Y-m-d');
		//echo $date->format('m');
		$month=$date->format('m');
		$day=$date->format('d');
		
		if(strcmp($day, "01")==0)
		{
			if(strcmp($month, "01")==0)
				return "January";
			else if(strcmp($month, "02")==0)
				return "February";
			else if(strcmp($month, "03")==0)
				return "March";
			else if(strcmp($month, "04")==0)
				return "April";
			else if(strcmp($month, "05")==0)
				return "May";
			else if(strcmp($month, "06")==0)
				return "June";
			else if(strcmp($month, "07")==0)
				return "July";
			else if(strcmp($month, "08")==0)
				return "August";
			else if(strcmp($month, "09")==0)
				return "September";
			else if(strcmp($month, "10")==0)
				return "October";
			else if(strcmp($month, "11")==0)
				return "November";
			else
				return "December";
		}
		else
		{
			if(strcmp($day, "20")==0) //Spring
				return "Spring";
			else if(strcmp($day, "21")==0) //Summer
				return "Summer";
			else if(strcmp($day, "22")==0) //Fall
				return "Fall";
			else if(strcmp($day, "23")==0) //Winter
				return "Winter";
			else if(strcmp($day, "30")==0) //Annual
				return "Annual";
			else if(strcmp($day, "31")==0) //OGN
				return "Original Graphic Novel";
			else
			{
				echo "I don't know what you did, but you screwed something up!!!<br>";
				echo "PubDate: $pubDate<br>";
				echo "Month: $month<br>";
				echo "Day: $day<br>";
			}
		}
	}
	
	function getYear($pubDate)
	{
		$date = DateTime::createFromFormat('Y-m-d', $pubDate);
		//echo $date->format('Y-m-d');
		//echo $date->format('m');
		
		return $date->format('Y');
	}
	
	function getMonthNum($monthStr)
	{
		if(strcmp($monthStr, "January")==0)
				return "-01-01";
			else if(strcmp($monthStr, "February")==0)
				return "-02-01";
			else if(strcmp($monthStr, "March")==0)
				return "-03-01";
			else if(strcmp($monthStr, "April")==0)
				return "-04-01";
			else if(strcmp($monthStr, "May")==0)
				return "-05-01";
			else if(strcmp($monthStr, "June")==0)
				return "-06-01";
			else if(strcmp($monthStr, "July")==0)
				return "-07-01";
			else if(strcmp($monthStr, "August")==0)
				return "-08-01";
			else if(strcmp($monthStr, "September")==0)
				return "-09-01";
			else if(strcmp($monthStr, "October")==0)
				return "-10-01";
			else if(strcmp($monthStr, "November")==0)
				return "-11-01";
			else if(strcmp($monthStr, "December")==0)
				return "-12-01";
			else if(strcmp($monthStr, "Spring")==0)
				return "-03-20";
			else if(strcmp($monthStr, "Summer")==0)
				return "-06-21";
			else if(strcmp($monthStr, "Fall")==0)
				return "-09-22";
			else if(strcmp($monthStr, "Winter")==0)
				return "-12-23";
			else if(strcmp($monthStr, "Annual")==0)
				return "-12-30";
			else if(strcmp($monthStr, "Original Graphic Novel")==0)
				return "-01-31";
	}
	
	function logEvent($cxn, $event)
	{
		if($cxn == null)
			return;
		
		$uname = $_SESSION['uname'];
		$insertLog="INSERT INTO UserLog (UserName, Event, TimeStamp) VALUES (\"$uname\", \"$event\", CURRENT_TIMESTAMP)";
		
		mysqli_query($cxn,$insertLog)or die("Could not insert into log. "." $insertLog ".mysqli_error($cxn)." ".mysqli_error());
	}
	
	function deleteOldEvents($cxn)
	{
		if($cxn == null)
			return;
			
		//Delete logs more than 2 years old
		$uname = $_SESSION['uname'];
		$deleteLogs="delete from UserLog where date(`TimeStamp`) < DATE_SUB(NOW(), INTERVAL 2 YEAR) and UserName = \"$uname\"";
		mysqli_query($cxn,$deleteLogs)or die("Could not delete old logs. "." $deleteLogs ".mysqli_error($cxn)." ".mysqli_error());
	}
?>