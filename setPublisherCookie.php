<?php
session_start();
echo "HERE!!!!!!!!!!!!!!!!!!";
@setcookie($_POST['title'], $_POST['Publisher'], time()+3600); //Expires in 1 hour.
?>