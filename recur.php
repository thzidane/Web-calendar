<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'module5database.php';
header("Content-Type: application/json");
$day = htmlentities($_POST['recurDay']);
$time = htmlentities($_POST['recurTime']);
$info = htmlentities($_POST['recurEvent']);

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



$stmt = $mysqli->prepare("insert into recurevents (username, day,event_time,event_info) values (?,?,?,?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->bind_param('ssss', $username,$day,$time,$info);
 
$stmt->execute();
$stmt->close();

?>