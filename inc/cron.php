<?php

require_once ("/usr/share/nginx/www/inc/var.php");

echo "Starting Cron" . PHP_EOL.PHP_EOL;

$called_from_cron = true;

$sql_s_c = "SELECT device, type, short_name, goal, goal_device, on_goes, start, end, TIMESTAMPDIFF(MINUTE, updated_on, NOW()) AS updated_since FROM {$status}";
$query_s_c = mysqli_query($link, $sql_s_c);

while ($row_s_c = mysqli_fetch_assoc($query_s_c)) {
	$current_status[$row_s_c['device']] = $row_s_c['short_name'];
	if ($row_s_c['goal'] != '') {
		 $current_status[$row_s_c['device']] .= ' > '.$row_s_c['goal'];
	}
	if (($row_s_c['device'] == 'Temperature') && ($row_s_c['updated_since'] > 60)) {
		echo ninjaSendMail("Ninja Alert", "No Temprature update for more than ".$row_s_c['updated_since']." minutes").PHP_EOL.PHP_EOL;
	}
}

print_r($current_status);

mysqli_data_seek($query_s_c, 0);

while ($row_s_c = mysqli_fetch_assoc($query_s_c)) {
    if ($row_s_c['type'] == 'Actuator') {
        echo 'Checking '. $row_s_c['device'] . PHP_EOL;
        $_POST['device'] = $row_s_c['device'];
        $_POST['short_name'] = $row_s_c['short_name'];
        $_POST['goal'] = $row_s_c['goal'];
        $_POST['start'] = $row_s_c['start'];
        $_POST['end'] = $row_s_c['end'];
        $_POST['action'] = 'PUT';
	if (!(isset($web_app_call)) || ($web_app_call !== true)) {
        	$_POST['token'] = '354436053785190';
	}
        if ($_POST['goal'] != '') {
            $current = $current_status[$row_s_c['goal_device']];
	    if ($row_s_c['on_goes'] == 'up') {
                if ($_POST['goal'] < $current) {
                    $short_name = 'Off';
                }
                else {
                    $short_name = 'On';
                }
            }
            else {
               if ($current < $_POST['goal']) {
                   $short_name = 'On';
               }
               else {
                   $short_name = 'Off';
               }
            }
            if ($_POST['short_name'] != $short_name) {
                echo 'Adjusting '. $_POST['device']. PHP_EOL;
                $sql_s_c_2 = "SELECT device, da FROM {$devices} WHERE device = '{$_POST['device']}' AND short_name = '{$short_name}'";
                $query_s_c_2 = mysqli_query($link, $sql_s_c_2);
                $row_s_c_2 = mysqli_fetch_array($query_s_c_2);
                $_POST['short_name'] = $short_name;
                $_POST['da'] = $row_s_c_2['da'];
            }
        }
	include ($root."/action.php");
	echo PHP_EOL.PHP_EOL;
    }

}

echo "Cron Finished" . PHP_EOL;
?>

