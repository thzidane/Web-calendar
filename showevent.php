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
$username = $_SESSION['username'];
if($_SESSION['token'] !== $_POST['token']){
	die("Request forgery detected");
}
//select the user his own event
$stmt = $mysqli->prepare("select event_date,event_time,event_info from events where username = ?  ");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
 
$stmt->bind_param('s', $username); 
$stmt->execute();


$result = $stmt->get_result();
$date = array();
$time = array();
$info = array();
while($row = $result->fetch_assoc()){
    $date[]=htmlentities($row["event_date"]);
    $time[]=htmlentities($row["event_time"]);
    $info[]=htmlentities($row["event_info"]);
}
$stmt->close();

//select who share the calendar with the user.
$stmt = $mysqli->prepare("select username from share where shareuser  = ?  ");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
 
$stmt->bind_param('s', $username); 
$stmt->execute();


$result = $stmt->get_result();
$name[]=array();
while($row = $result->fetch_assoc()){
    $name[] = htmlentities($row['username']);
}
$stmt->close();

//select all the events of the person who share with login user.
//if no one share with the login user. then count($name)=0 which means the next for loop will not run.
for($j=0;$j<count($name);$j++){
    $stmt = $mysqli->prepare("select event_date,event_time,event_info from events where username = ?  ");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
 
$stmt->bind_param('s', $name[$j]); 
$stmt->execute();


$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    $date[]=htmlentities($row["event_date"]);
    $time[]=htmlentities($row["event_time"]);
    $info[]=htmlentities($row["event_info"]);
}
$stmt->close();
}





$newArray = array();
$newArray['cnt'] = count($date);
for($i = 0;$i<count($date);$i++){
    
    $newArray['date'.$i] = $date[$i];
    $newArray['time'.$i] = $time[$i];
    $newArray['info'.$i] = $info[$i];
}

echo json_encode($newArray);
?>