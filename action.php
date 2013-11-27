<?php

require_once ("/usr/share/nginx/www/inc/var.php");

if ((isset($_POST['token'])) && ($_POST['token'] != '')) {
        $_POST['token'] = mysqli_real_escape_string($link, $_POST['token']);
        $sql = "SELECT id, login, password, role FROM {$users} WHERE token='{$_POST['token']}'";
        $row = mysqli_fetch_array(mysqli_query($link, $sql));
        if ($row['id']) {
                $_SESSION['id'] = $row['id'];
		$_SESSION['password'] = $row['password'];
                $_SESSION['login'] = $row['login'];
                $_SESSION['role'] = $row['role'];
        }
}

if ((isset($_SESSION['role'])) && ($_SESSION['role'] == 'demo')) {
    $response = 'Fake 200';
    echo $response;
}
else if ((isset($_SESSION['login'])) && ($_SESSION['login'] != '')) {

    /**
    * include the Ninja Blocks API library
    */
    require_once ($root."/inc/nbapi.php");

    /**
     * Instantiate a new Device API.
     * This object will be used to communicate with the Ninja Blocks API.
     */
    $deviceAPI = new Device();

    $action = mysqli_real_escape_string($link, $_POST['action']);
    $device = mysqli_real_escape_string($link, $_POST['device']);
    $short_name = mysqli_real_escape_string($link, $_POST['short_name']);

    if ($device == 'All') {
            $guid = 'All';
    }
    else {
        if ($action == 'GET') {
            $sql = "SELECT id, guid, type, short_name, da, goal, goal_device, on_goes, start, end FROM {$status} WHERE device = '{$device}'";
        }
        else {
            $sql = "SELECT id, guid, da, goal_device, on_goes, type FROM {$devices} WHERE device = '{$device}' AND short_name='{$short_name}'";
        }
        $row = mysqli_fetch_array(mysqli_query($link, $sql));
        if ($row['guid']) {
            $guid = $row['guid'];
            if ($action != 'GET') {
                $da = $row['da'];
		$goal_device = $row['goal_device'];
		$on_goes = $row['on_goes'];
		$type = $row['type'];
            }
        }
        else {
            header('HTTP/1.1 500 Invalid Device/Short Name');
            header('Content-Type: application/json');
            die('Invalid Device Parameters');
        }
    }

    if (($action == 'GET') && ($guid == '')) {
            if ($_SESSION['role'] == 'admin') {
                $response = print_r($deviceAPI->getDevices());
                putInLogs($_SESSION['login'], 'Get devices', 'All for admin', '', '');
            }
            else {
                header('HTTP/1.1 500 Invalid Credentials');
                header('Content-Type: application/json');
                die('Invalid Credentials');
            }
    }
    elseif (($action == 'GET') && ($guid == 'All')) {
        $sql = "SELECT device, short_name, goal, goal_device, start, end, TIMESTAMPDIFF(MINUTE, updated_on, NOW()) AS updated_since FROM {$status}";
        $query = mysqli_query($link, $sql);
        $rows = array();
        while ($row = mysqli_fetch_array($query)) {
            $rows[] = $row;
        }
        $response = json_encode($rows);
    }
    elseif ($action == 'GET') {
            $response = ucfirst($row['short_name']);
            putInLogs($_SESSION['login'], 'Get device', $device, $row['short_name'], '');
    }
    elseif ($action == 'PUT') {
            $actuateData = (object) array('DA' => $da, 'shortName' => $short_name);
            $actuateResponse = $deviceAPI->actuate($guid, $actuateData);
            $response = print_r($actuateResponse);
            //Update status (for get and cron)
            $goal = mysqli_real_escape_string($link, $_POST['goal']);
            $start = mysqli_real_escape_string($link, $_POST['start']);
            $end = mysqli_real_escape_string($link, $_POST['end']);
            $sql = "UPDATE {$status} SET login = '{$_SESSION['login']}', guid = '{$guid}', type = '{$type}', short_name = '{$short_name}', da = '${da}', goal = '{$goal}', goal_device = '{$goal_device}', on_goes = '{$on_goes}', start = '{$start}', end = '{$end}', updated_on = now() WHERE device = '{$device}'";
            $query = mysqli_query($link, $sql) or die (mysqli_error());
    }

    echo $response;
    if (($action == 'PUT') && ($goal != '') && ($called_from_cron !== true)) {
	fastcgi_finish_request();
	$web_app_call = true;
        include ($root."/inc/cron.php");
    }
}
else {
    header('HTTP/1.1 500 Invalid Credentials');
    header('Content-Type: application/json');
    die('Invalid Credentials');
}
?>
