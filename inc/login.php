<?

$_POST['login'] = mysqli_real_escape_string($link, $_POST['login']);
$_POST['password'] = md5(mysqli_real_escape_string($link, $_POST['password']));

// Escaping all input data
$sql = "SELECT id, login, password, role FROM {$users} WHERE login = '{$_POST['login']}' AND password = '{$_POST['password']}'";
$row = mysqli_fetch_assoc(mysqli_query($link, $sql));
if ($row['id']) {
	$_SESSION['id'] = $row['id'];
	$_SESSION['login'] = $row['login'];
	$_SESSION['role'] = $row['role'];
	$_SESSION['password'] = $row['password'];
	setcookie('NinjaKLogin', $_SESSION['login']);
	setcookie('NinjaKPass', $_SESSION['password']);
	header("Location: /");
	exit;

}
else {
	$error = 'Error, please check your information';
}

?>
