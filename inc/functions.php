<?php
//putInLogs(login, action, device, short_name, da);
if (!(function_exists('putInLogs'))) {
	function putInLogs($login, $action, $device, $short_name, $da) {
		//Adding item to log
		$logs = $GLOBALS['logs'];
		$sql = "INSERT INTO {$logs} (login, action, device, short_name, da) values ('{$login}', '{$action}', '{$device}', '{$short_name}', '{$da}')";
		$query = mysqli_query($link, $sql) or die (mysqli_error());

		//Cleaning logs will be included in cron for lighter real time execution)
		//$log_limit = 10;
		//$sql = "DELETE FROM {$logs} WHERE id NOT IN (SELECT id FROM ( SELECT id FROM $logs ORDER BY id DESC LIMIT {$log_limit}) x)";
		//$query = mysqli_query($link, $sql) or die (mysqli_error());

	}
}

if (!(function_exists('ninjaSendMail'))) {
	function ninjaSendMail($subject, $content) {

		$mail = new PHPMailer;

		$mail->IsSMTP();                                      // Set mailer to use SMTP
		$mail->Host = "SMTP SERVER";                       // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = "USERNAME";                            // SMTP username
		$mail->Password = "PASSWORD";                           // SMTP password
		$mail->SMTPSecure = "tls";                            // Enable encryption, 'ssl' also accepted

		$mail->From = "FROM";
		$mail->FromName = "me";
		$mail->AddAddress("TO ADDRESS", "TO NAME");  // Add a recipient
		$mail->AddReplyTo("REPLY ADDRESS", "REPLY NAME");

		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		$mail->IsHTML(true);                                  // Set email format to HTML

		$mail->Subject = $subject;
		$mail->Body    = $content;

		if (!$mail->Send()) {
			return "Mail Error: " . $mail->ErrorInfo;
		}
		else {
			return "Message has been sent";
		}
	}
}

?>
