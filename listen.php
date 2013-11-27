<?php
include ($_SERVER['DOCUMENT_ROOT']."/inc/var.php");


if ((isset($_POST['token'])) && ($_POST['token'] != '')) {
        $_POST['token'] = mysqli_real_escape_string($link, $_POST['token']);
        $sql = "SELECT id, login, role FROM {$users} WHERE token='{$_POST['token']}'";
        $row = mysqli_fetch_array(mysqli_query($link, $sql));
        if ($row['id']) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['login'] = $row['login'];
                $_SESSION['role'] = $row['role'];
        }
}

if ((isset($_SESSION['role'])) && ($_SESSION['role'] == 'demo')) {
    echo 'Fake 200';
}
else if ((isset($_SESSION['login'])) && ($_SESSION['login'] != '')) {

    $guid = mysqli_real_escape_string($link, $_POST['guid']);
    $da = mysqli_real_escape_string($link, $_POST['da']);
    $sql = "SELECT device, type, short_name, goal_device, on_goes FROM {$devices} WHERE type = 'Sensor' AND guid= '{$guid}' AND (da = '{$da}' OR da = '')";
    $row = mysqli_fetch_array(mysqli_query($link, $sql));

    if ($row['device']) {
        $device = $row['device'];
	if ($row['short_name'] == '') {
        	$short_name = $da;
	}
	else {
		$short_name = $row['short_name'];
	}
	$goal_device = $row['goal_device'];
	$on_goes = $row['on_goes'];
	$type = $row['type'];
	$sql = "UPDATE {$status} SET login = '{$_SESSION['login']}', guid = '{$guid}', type = '{$type}', short_name = '{$short_name}', goal_device = '{$goal_device}', on_goes = '{$on_goes}', da = '${da}', updated_on = now() WHERE device = '{$device}'";
	$query = mysqli_query($link, $sql) or die (mysqli_error());
    }
    else {
        header('HTTP/1.1 500 Invalid Device/Short Name');
        header('Content-Type: application/json');
        die('Invalid Device Parameters');
    }
}
else {
    header('HTTP/1.1 500 Invalid Credentials');
    header('Content-Type: application/json');
    die('Invalid Credentials');
}
?>
