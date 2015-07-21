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
$add_date = htmlentities($_POST['addDate']);
$add_time = htmlentities($_POST['addTime']);
$add_event = htmlentities($_POST['addEvent']);
$username = $_SESSION['username'];

if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}

$stmt = $mysqli->prepare("insert into events (username, event_date, event_time, event_info) values (?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ssss', $username, $add_date, $add_time, $add_event);
 
$stmt->execute();
 
$stmt->close();
        
        header("Location: module5calendar.html");
        exit;
?>