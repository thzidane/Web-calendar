<?php
ini_set("session.cookie_httponly", 1);
session_start();
$previous_ua = @$_SESSION['useragent'];
$current_ua = $_SERVER['HTTP_USER_AGENT'];
 
if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
	die("Session hijack detected");
}else{
	$_SESSION['useragent'] = $current_ua;
}
require 'module5database.php';
header("Content-Type: application/json");
$delete_date = htmlentities($_POST['deleteDate']);
$delete_time = htmlentities($_POST['deleteTime']);
$username = $_SESSION['username'];
if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
$stmt = $mysqli->prepare("delete from events where event_date=? and event_time=? and username=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('sss', $delete_date, $delete_time, $username);
 
$stmt->execute();
 
$stmt->close();  
        header("Location: module5calendar.html");
        exit;
?>