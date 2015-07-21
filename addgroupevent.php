<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'module5database.php';
header("Content-Type: application/json");
$add_date = htmlentities($_POST['addDate']);
$add_time = htmlentities($_POST['addTime']);
$add_event = htmlentities($_POST['addEvent']);
$addUsername = htmlentities($_POST['addUsername']);
$exist = false;

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



$stmt = $mysqli->prepare("select event_date,event_time,event_info from events where username = ?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->bind_param('s', $username);
 
$stmt->execute();
 
$stmt->bind_result($first,$second, $last);
 

while($stmt->fetch()){
    if($first==$add_date&&$second==$add_time&&$last==$add_event){
        $exist = true;
    }
}

 
$stmt->close();





//to see whether the event has exisited on the database.


if($exist){
$stmt = $mysqli->prepare("insert into events (username, event_date, event_time, event_info) values (?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ssss', $addUsername, $add_date, $add_time, $add_event);
 
$stmt->execute();
 
$stmt->close();
}else{

$stmt = $mysqli->prepare("insert into events (username, event_date, event_time, event_info) values (?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ssss', $username, $add_date, $add_time, $add_event);
 
$stmt->execute();
 
$stmt->close();


$stmt = $mysqli->prepare("insert into events (username, event_date, event_time, event_info) values (?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('ssss', $addUsername, $add_date, $add_time, $add_event);
 
$stmt->execute();
 
$stmt->close();
}









//if($exist){
    //the event has exits on the users events. I only need to add to group user.


?>

