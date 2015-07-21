<?php
// login_ajax.php
 
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
require 'module5database.php';
$username = htmlentities($_POST['username']);
$password = htmlentities($_POST['password']);
 
// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

 
// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*), password FROM users WHERE username=?");
 
// Bind the parameter
$stmt->bind_param('s', $username);
//$user = $_POST['username'];
$stmt->execute();
 
// Bind the results
$stmt->bind_result($cnt, $pwd_hash);
$stmt->fetch();
 

if( $cnt == 1 && crypt($password, $pwd_hash)==$pwd_hash){
	ini_set("session.cookie_httponly", 1);
        session_start();
        
	$_SESSION['username'] = $username;
	$_SESSION['token'] = substr(md5(rand()), 0, 10);
	echo json_encode(array(
		"success" => true,
                "token" => htmlentities($_SESSION['token'])
	));
	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>