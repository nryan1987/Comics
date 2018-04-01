<?php
include 'addList.php';
session_start();
$cxn=mysqli_connect("localhost",$_SESSION['uname'],$_SESSION['pswrd'],"ryanbran_Comics") or die ("Could not connect");
?>
<script>
	var JSDATA = <?=json_encode($title, JSON_HEX_TAG | JSON_HEX_AMP)?>;
	valideopenerform(JSDATA.Title);
</script>
<script type='text/javascript'>
function valideopenerform(title){
	var URL = 'publisherPopup.php?pub=';
	URL = URL.concat(title);
	var pubPopup = window.open(URL,'popup_form','location=no,menubar=no,status=no,top=50%,left=50%,height=250,width=750')
	if(pubPopup.closed)
	{
		alert("POPUP CLOSED!!");
	}
	else
	{
		alert("Still open");
	}
}
</script>
<?php
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
if(!strrpos($_FILES["file"]["name"],".txt"))
{
	echo "Please upload a \".txt\" tab delimited file.<br>";
	echo "<a href=\"http://ryan-brannan.com/uploadFile.php\">Back</a> <br>";
}
else
{
  $file=fopen($_FILES["file"]["tmp_name"],"r") or exit("Cannot open file ".$_FILES["file"]["name"]);
  while(!feof($file))
  {
	$line=htmlspecialchars(fgets($file));
	list($title,$vol,$num,$notes,$paid)=split("\t",$line,5);
	$title = trim($title);
	$title = mysql_real_escape_string($title);
	$paid=str_replace("$","",$paid);
	$paid=str_replace("-","0.00",$paid);
	if(empty($vol))
		$vol=1;
	
	if(!empty($title))
	{
		$publisherSQL="SELECT DISTINCT Publisher FROM `Comics` WHERE Title =\"$title\"";
		$publisherResult=mysqli_query($cxn,$publisherSQL);
	
		if($publisherResult->num_rows==0)
		{
			
			$jsTitle = json_encode($title);
			
			echo "<html>
			<script type=\"text/javascript\">
				valideopenerform(".$jsTitle.");
			</script>
			</html>";
			//$Publisher = $_COOKIE["publisher"];
		}
		else	
		{
			$row=mysqli_fetch_assoc($publisherResult);
			extract($row);
			@setcookie($title, $Publisher, time()+3600); //Expires in 1 hour.
		}
	}
	else
		break;
  }
}
processFile($cxn, $_FILES["file"]);
?>