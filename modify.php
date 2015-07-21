<?php
require 'module5database.php';
ini_set("session.cookie_httponly", 1);
session_start();
$previous_ua = @$_SESSION['useragent'];
$current_ua = $_SERVER['HTTP_USER_AGENT'];
 
if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
	die("Session hijack detected");
}else{
	$_SESSION['useragent'] = $current_ua;
}

if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
$username = $_SESSION['username'];

$date = htmlentities($_POST['originalDate']);
$time = htmlentities($_POST['originalTime']);
$newDate = htmlentities($_POST['updateDate']);
$newTime = htmlentities($_POST['updateTime']);
$newInfo = htmlentities($_POST['updateEvent']);

$stmt = $mysqli->prepare("update events set event_date=?,event_time=?,event_info=? where username = ? and event_date = ? and event_time = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
 
$stmt->bind_param('ssssss', $newDate,$newTime,$newInfo,$username,$date,$time); 
$stmt->execute();
$stmt->close();

?>