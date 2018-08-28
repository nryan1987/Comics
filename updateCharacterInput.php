<?php
$ID=$_GET['id'];
session_start();
$cxn=mysqli_connect("localhost", $_SESSION['uname'], $_SESSION['pswrd'], $_SESSION['dbName']) or header("Location: index.php?login=false");
$selectCharacter="SELECT * FROM Characters WHERE CharacterID='$ID'";
$result=mysqli_query($cxn,$selectCharacter);
$row=mysqli_fetch_assoc($result);
extract($row);

$sql="SELECT Alias FROM CharacterAliases WHERE CharacterID='$ID' ORDER BY Alias";
$aliasResult=mysqli_query($cxn,$sql)or die("Could not search for character alias");
?>
<html>
<head><title><?php echo $Characters ?></title></head>
<body bgcolor="#408080" text="#FFFFFF">
<form action='updateCharacter.php?id=<?php echo "$CharacterID"; ?>' method='GET'>
<table Border='1'>
<tr>
<td>CharacterID:</td>
<td><input type="text" size="40" name="characterID" value="<?php echo "$CharacterID"; ?>" /></td></tr>
<td>Character: </td>
<td><input type="text" size="40" name="character" value="<?php echo "$Characters"; ?>" /></td>
</tr>
<td>Picture:</td>
<td><input type="text" size="60" name="picture" value="<?php if(empty($CharacterPic)){echo "/images/Characters/$Characters";} else echo "$CharacterPic"; ?>" /></td></tr>
<td>Aliases:</td>
<td><input type="text" size="60" name="newAlias"/></td>
<?php 
while($aliasRow=mysqli_fetch_assoc($aliasResult))
{
	extract($aliasRow);
	echo "<tr>\n
	<td>$Alias</td>";
	echo "<td><input type='checkbox' name='aliasFields[]' value='$Alias' /> Delete $Alias</td></tr>";
}
?>
</table> 
<br>
<?php echo '<img STYLE="position:absolute; TOP:7px; LEFT:650px;" src="'.$CharacterPic.'" ALT="Picture unavailable">' ?>
<br>
<br>
<input type='submit' value='Update Character' /><br>
<a href="menu.php">Back to main menu</a> <br>
</body></html>