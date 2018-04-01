<?php
session_start();
?>
<html>
<head><title>COMICS</title></head>
<body bgcolor="#408080" text="#FFFFFF">
<form action="addList.php" method="post" enctype="multipart/form-data">
<p>The format of the file should be title, volume, issue number, notes, price paid.</p>
<label for="file">Filename:</label>
<input type="file" name="file" id="file" />
<br />
<input type="submit" name="submit" value="Submit" />
</form>
</body>
</html>