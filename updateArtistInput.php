<?php
$ID=$_GET['id'];
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$selectComic="SELECT * FROM Artists WHERE ArtistID='$ID'";
$result=mysqli_query($cxn,$selectComic);
$row=mysqli_fetch_assoc($result);
extract($row);
$sql="SELECT Alias FROM ArtistAlias WHERE ArtistID='$ID' ORDER BY Alias";
$aliasResult=mysqli_query($cxn,$sql)or die("Could not search for artist alias");
?>
<html>
<head><title><?php echo $Artist ?></title></head>
<body bgcolor="#408080" text="#FFFFFF">
<form action='updateArtist.php?id=<?php echo "$ArtistID"; ?>' method='GET'>
<table Border='1'>
<tr>
<td>ArtistID:</td>
<td><input type="text" size="40" name="artistID" value="<?php echo "$ArtistID"; ?>" /></td></tr>
<td>Artist: </td>
<td><input type="text" size="40" name="Artist" value="<?php echo "$Artist"; ?>" /></td>
</tr>
<tr>
<td>Picture:</td>
<td><input type="text" size="50" name="picture" value="<?php echo "$artistPic"; ?>" /></td>
</tr>
<td>Enter a new alias:</td>
<td><input type="text" size="50" name="creditedAs"/></td>
<?php 
while($aliasRow=mysqli_fetch_assoc($aliasResult))
{
	extract($aliasRow);
	echo "<tr>\n
	<td>$Alias</td>";
	echo "<td><input type='checkbox' name='aliasFields[]' value='$Alias' /> Delete $Alias</td></tr>";
}
?>
</tr>
</table> 
<br>
<?php echo '<img STYLE="position:absolute; TOP:7px; LEFT:550px;" src="'.$artistPic.'" ALT="Picture unavailable">' ?>
<br>
<br>
<input type='submit' value='Update artist' /><br>
<a href="menu.php">Back to main menu</a> <br>
</body></html>