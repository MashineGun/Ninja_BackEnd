<?php

/* DB Tables*/
$users = "users";
$devices = "devices";
$logs = "logs";
$status = "status";

/* DB parameters*/
$mysqluser = "localhost";
$mysqllog = "USER";
$mysqlpass = "PASSWORD";
$mysqlbase = "ninja";

/* DB connection */
$link = mysqli_connect($mysqluser, $mysqllog, $mysqlpass, $mysqlbase);

?>
