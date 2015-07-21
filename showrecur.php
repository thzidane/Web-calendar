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

//select the user his own event
$stmt = $mysqli->prepare("select day,event_time,event_info from recurevents where username = ?  ");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
 
$stmt->bind_param('s', $username); 
$stmt->execute();


$result = $stmt->get_result();
$day = array();
$time = array();
$info = array();
while($row = $result->fetch_assoc()){
    $day[]=htmlentities($row["day"]);
    $time[]=htmlentities($row["event_time"]);
    $info[]=htmlentities($row["event_info"]);
}
$stmt->close();

$newArray = array();
$newArray['cnt'] = count($day);
for($i = 0;$i<count($day);$i++){
    
    $newArray['day'.$i] = $day[$i];
    $newArray['time'.$i] = $time[$i];
    $newArray['info'.$i] = $info[$i];
}

echo json_encode($newArray);
?>