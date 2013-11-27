<?php

/* Session infos */
session_name('NinjaK');
session_set_cookie_params(2*7*24*60*60); // Making the cookie live for 2 weeks
session_start();

// Set display errors
ini_set('display_errors', 0);
error_reporting(E_ALL);

//Check is cookie exists
if ((isset($_COOKIE['NinjaKLogin'])) && (isset($_COOKIE['NinjaKPass']))) {
	$_SESSION['login'] = $_COOKIE['NinjaKLogin'];
	$_SESSION['password'] = $_COOKIE['NinjaKPass'];
}

$root = "/usr/share/nginx/www";
require_once ($root."/inc/db.php");
require_once ($root."/inc/functions.php");
require_once ($root."/inc/class.phpmailer.php");

//Checking that account is still active
if (isset($_SESSION['login'])) {
	$sql = "SELECT id, login, role FROM " . $users . " WHERE login = '{$_SESSION['login']}' AND password = '{$_SESSION['password']}'";
	$row = mysqli_fetch_array(mysqli_query($link, $sql));
	if ($row['id']) {
		$_SESSION['id'] = $row['id'];
		$_SESSION['role'] = $row['role'];
		if (!(headers_sent())) {
			setcookie('NinjaKLogin', $_SESSION['login']);
			setcookie('NinjaKPass', $_SESSION['password']);
		}
	}
 	else {
		$_SESSION = array();
		session_destroy();
		header("Location: /");
		exit;
	}
}

?>
