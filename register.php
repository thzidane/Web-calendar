<?php
require 'module5database.php';
header("Content-Type: application/json");
$username = htmlentities($_POST['registername']);
$password = htmlentities($_POST['registerpassword']);
$hashpassword = crypt("$password", '$1$WQvMDFgI$5.mVOS7V2Q/aB78Mxl13Q1');

$stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ss', $username, $hashpassword);
 
$stmt->execute();
 
$stmt->close();
        
        header("Location: module5calendar.html");
        exit;
?>