<head>
<script>
function sendValue()
{
	//window.alert("TEST");
	var e = document.getElementById("Publisher");
	var strUser = e.options[e.selectedIndex].value;
	window.alert(strUser);
	//var parentId = <?php echo json_encode($_GET['id']); ?>;
	window.opener.test(strUser);
	//window.opener.updateValue(value);
	//updateValue(value);
	window.close();
}

function myFunction() {
    var e = document.getElementById("Publisher");
	var publisher = e.options[e.selectedIndex].value;
	document.getElementById("demo").innerHTML = publisher;
}
</script>
</head>
There is no publisher information for <?php echo $_GET['id']?>. Choose publisher.
</br>
<p id="demo" onclick="myFunction()">Click me to change my HTML content (innerHTML).</p>
<td>Publisher:</td>
<td><select name ='Publisher' id = 'Publisher'>

<?php

	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);
	$cxn=mysqli_connect("localhost","root","bro3886","Comics") or header("Location: index.php?login=false");
	$sql="SELECT DISTINCT Publisher FROM Publisher ORDER BY Publisher";
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		echo "<option value=\"$Publisher\">$Publisher</option>\n";
	}
?>
</select></td>
<tr>
<td><input type="button" value="Select" onClick="sendValue()" /></td>
</tr>
