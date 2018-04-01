<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<body bgcolor="#000000" text="#FFFFFF">
<head><title>COMICS</title></head>
<center><img src="../images/heroes.gif" ALT="HELLO"/></center>

<br />
<?php
$login = $_GET['login'];
if(!(empty($login)))
		echo "<html><center><font color=\"red\">***ERROR. USER ID/PASSWORD INCORRECT***</font></center></html>";
?>

<form name="form1" method="post" action="menu.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="black" style="color:white">
<tr>
<td colspan="3"><strong>Member Login </strong></td>
</tr>
<tr>
<td width="78">User ID</td>
<td width="6">:</td>
<td width="294"><input name="myusername" type=text id="myusername"></td>
</tr>
<tr>
<td>Password</td>
<td>:</td>
<td><input name="mypassword" type="password" id= "mypassword"></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Login"></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
