<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'module5database.php';
header("Content-Type: application/json");
$shareUsername = htmlentities($_POST['shareUser']);


$previous_ua = @$_SESSION['useragent'];
$current_ua = $_SERVER['HTTP_USER_AGENT'];
 
if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
	die("Session hijack detected");
}else{
	$_SESSION['useragent'] = $current_ua;
}
 

$username = $_SESSION['username'];

if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}



$stmt = $mysqli->prepare("insert into share (username, shareuser) values (?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->bind_param('ss', $username,$shareUsername);
 
$stmt->execute();
$stmt->close();

?>