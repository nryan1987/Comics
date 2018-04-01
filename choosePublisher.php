<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
$title=$_GET['title'];
?>
<html>
<script language="javascript" type="text/javascript">
function windowClose() {
window.open('','_parent','');
window.close();
}
</script>
<head><title>COMICS</title></head>
<body bgcolor=#408080 text=#FFFFFF>
<?php
echo "There is no publisher information for $title. Choose a publisher from the drop down list or enter a new publisher.";
?>
<form action=\"addList.php\" method=\"post\" enctype=\"multipart/form-data\">
<table>
<td>Publisher:</td>
<td><select name ='Publisher'>
<?php
	$sql="SELECT DISTINCT Publisher FROM Publisher ORDER BY Publisher";
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		echo "<option value=\"$Publisher\">$Publisher</option>\n";
	}
?>
</select></td>
<td>Or enter a new publisher:</td>
<td><input type="text" size='30' name="alternatePublisher" /></td>
</tr>
</table>
<input type="button" value="Close this window" onclick="windowClose();">
</body>
</html>