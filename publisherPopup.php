<?php
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"Comics") or die ("Could not connect");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Opener</title>
<script type='text/javascript'>
function validepopupform(titleStr){
	//document.cookie = "publisher=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/addList.php; domain=krypton"; //Deletes the current cookie.
	var e = document.getElementById("Publisher");
	var publisher = e.options[e.selectedIndex].value;
	var cleanTitle = cleanString(titleStr);
	deleteCookie(cleanTitle);
	setCookie(cleanTitle, publisher, 1, '/', '', '');
	//window.opener.test(publisher);
	self.close();
}
function getCookie(w){
	cName = "Cookie not found.";
	pCOOKIES = new Array();
	pCOOKIES = document.cookie.split('; ');
	for(bb = 0; bb < pCOOKIES.length; bb++){
		NmeVal  = new Array();
		NmeVal  = pCOOKIES[bb].split('=');
		if(NmeVal[0] == w){
			cName = unescape(NmeVal[1]);
		}
	}
	return cName;
}
function setCookie(name, value, expires, path, domain, secure){
	cookieStr = name + "=" + escape(value) + "; ";
	
	if(expires){
		expires = setExpiration(expires);
		cookieStr += "expires=" + expires + "; ";
	}
	if(path){
		cookieStr += "path=" + path + "; ";
	}
	if(domain){
		cookieStr += "domain=" + domain + "; ";
	}
	if(secure){
		cookieStr += "secure; ";
	}
	
	document.cookie = cookieStr;
}
function setExpiration(cookieLife){
    var today = new Date();
    var expr = new Date(today.getTime() + cookieLife * 30*10000);
    return  expr.toGMTString();
}
function cleanString(title)
{
	title = title.replace(" ","_");
	title = title.replace("'","");
	title = title.replace(".","");
	title = title.replace("?","");
	title = title.replace("!","");
	title = title.replace(","," ");
	title = title.replace("/","_");
	alert(title);
	return title;
}
function deleteCookie(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};
</script>
There is no publisher information for <input type="text" name="title" value="<?php echo $_GET['pub']?>" readonly> Choose publisher.<br>
</head>
<body bgcolor="#408080" text="#FFFFFF">
<form id='form2' name='form2' >
<select name ='Publisher' id = 'Publisher' method='post'>
<?php
	$sql="SELECT DISTINCT Publisher FROM Publisher ORDER BY Publisher";
	$result=mysqli_query($cxn,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		extract($row);
		echo "<option value=\"$Publisher\">$Publisher</option>\n";
	}
	echo "</select>";
	echo "<input type='button' value='SUBMIT' onclick='validepopupform(\"".$_GET['pub']."\")'/>";
	//echo "<input type='submit' value='SUBMIT' onclick='publisherPopup.php?pub=\"".$title."\"'/>";
?>
</form>
</body>
</html>