<?php
$ID=$_GET['id'];
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$selectComic="SELECT * FROM Creators WHERE CreatorID='$ID'";
$result=mysqli_query($cxn,$selectComic);
$row=mysqli_fetch_assoc($result);
extract($row);
$sql="SELECT Alias FROM CreatorAlias WHERE CreatorID='$ID' ORDER BY Alias";
$aliasResult=mysqli_query($cxn,$sql)or die("Could not search for creator alias. ".mysqli_error($cxn));
?>
<html>
<head><title><?php echo $Creator ?></title></head>
<body bgcolor="#408080" text="#FFFFFF">
<form action='updateCreator.php?id=<?php echo "$creatorID"; ?>' method='GET'>
<table Border='1'>
<tr>
<td>CreatorID:</td>
<td><input type="text" size="40" name="artistID" value="<?php echo "$CreatorID"; ?>" readonly/></td></tr>
<td>Creator: </td>
<td><input type="text" size="40" name="Artist" value="<?php echo "$Creator"; ?>" /></td>
</tr>
<tr>
<td>Picture:</td>
<td><input type="text" size="50" name="picture" value="<?php echo "$creatorPic"; ?>" /></td>
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
<?php echo '<img STYLE="position:absolute; TOP:7px; LEFT:550px;" src="'.$creatorPic.'" ALT="Picture unavailable">' ?>
<br>
<br>
<input type='submit' value='Update creator' /><br>
<a href="menu.php">Back to main menu</a> <br>
</body></html>