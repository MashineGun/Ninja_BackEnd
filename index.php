<?php
	include ($_SERVER['DOCUMENT_ROOT']."/inc/var.php");

//phpinfo();
?>

<?php
if ((isset($_GET['action'])) && ($_GET['action'] == 'logout')) {
	if (isset($_COOKIE['NinjaKLogin'])) {
		setcookie('NinjaKLogin', '', time()-60*60*24*100, '/');
                setcookie('NinjaKPass', '', time()-60*60*24*100, '/');
	}
	unset($_SESSION['login']);
	unset($_SESSION['id']);
	$_SESSION = array();
	session_destroy();
	header("Location: /");
	exit;
}
else if ($_GET['action'] == 'login') {
	include($root."/inc/login.php");
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Ninja K Dashboard</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="Web App For Ninja Block Control" />
		<meta name="keywords" content="" />
		<link rel="shortcut icon" href="/images/favicon.png">
		<link rel="stylesheet" href="css/main.css" />
		<link rel="stylesheet" href="css/toastr.css" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
		<script src="js/init.js"></script>
		<script src="js/toastr.js"></script>
		<script src="js/smoothscroll.js"></script>
		<script src="js/main.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-n1.css" />
		</noscript>
	</head>
	<body class="homepage">

		<!-- Header Wrapper -->
			<div id="header-wrapper">

				<!-- Header -->
					<div id="header" class="container">

						<!-- Logo -->
							<h1 id="logo"><a href="/">Ninja K</a></h1>

						<!-- Nav -->
							<nav id="nav">
								<ul>
									<li><?php if ((isset($_SESSION['role'])) && (($_SESSION['role'] == 'admin')) || ($_SESSION['role'] == 'demo')) { ?>
															<a href="/admin.php">Admin</a>
											<?php } ?>
									</li>
									<li><?php if (isset($_SESSION['login'])) { ?> <a href="?action=logout">Logout</a> <?php } ?></li>
								</ul>
							</nav>

					</div>

				<!-- Hero -->
					<section id="hero" class="container">
						<header>
							<h2>Ninja K Control Dashboard</h2>
						</header>

<?php

if (isset($_SESSION['login'])) {

?>
						<ul class="actions">
							<li><a href="#controls" class="button">Take me to the controls</a></li>
						</ul>
					</section>
<?php
}
else {

?>

						<div class="row">

<?php
if ((isset($error)) || ($error != '')) {
        echo $error.'<br>';
}
?>

							<section class="12u">
								<form name="login-form" id="login-form" method="POST" action="?action=login">
									<div class="row">
										<div class="6u">
											<input name="login" placeholder="Username" type="text" class="text" />
										</div>
										<div class="6u">
											<input name="password" placeholder="Password" type="password" class="text" />
										</div>
									</div>
									<div class="row half">
										<div class="12u">
											<ul class="actions">
												<li><a href="#" class="button submitForm-button" data-form-id="login-form">Login</a></li>
											</ul>
										</div>
									</div>
								</form>
							</section>
						</div>


<?php

}

?>
         </section>
			</div>

<?php

if (isset($_SESSION['login'])) {

?>

		<!-- Controls -->
			<div id="controls" class="wrapper">

				<div class="container">
					<div class="row">
						<section class="12u feature">
							<header>
								<h2 class="inline">Ninja Eyes</h2><a class="button inline" id="Eyes-status"></a>
							</header>
							<ul class="actions">
								<li>
									<ul class="dropdown" id="eyes-color">
										<li class="button">
											<a class="label" href="" value="black">Black</a>
											<ul>
												<li><a href="" value="white">White</a></li>
												<li><a href="" value="blue">Blue</a></li>
												<li><a href="" value="green">Green</a></li>
												<li><a href="" value="yellow">Yellow</a></li>
												<li><a href="" value="purple">Purple</a></li>
												<li><a href="" value="pink">Pink</a></li>
												<li><a href="" value="red">Red</a></li>
												<li><a href="" value="black">Black</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li>
									<ul class="actions">
										<li><a href="" class="button ninja-button" data-action="PUT" data-device="Eyes" data-short-name="#eyes-color" data-input-type="dropdown">Change color</a></li>
									</ul>
								</li>
							</ul>
						</section>
						<section class="12u feature">
							<header>
								<h2 class="inline">Living Room</h2><a class="button inline" id="Living-Room-status"></a>
							</header>
							<ul class="actions">
								<li>
									<ul class="dropdown" id="living-room-goal">
										<li class="button">
										<a class="label" href="" value="Off" data-goal="">Off</a>
										<ul>
											<li><a href="" value="On" data-goal="">On</a></li>
											<li><a href="" value="On" data-goal="10">10</a></li>
											<li><a href="" value="On" data-goal="18">18</a></li>
											<li><a href="" value="On" data-goal="20">20</a></li>
											<li><a href="" value="Off" data-goal="">Off</a></li>
										</ul>
										</li>
									</ul>
								</li>
								<li><a href="" class="button ninja-button" data-action="PUT" data-device="Living Room" data-short-name="#living-room-goal" data-input-type="dropdown">Change Living Room</a></li>
							</ul>
						</section>
						<section class="12u feature">
							<header>
									<h2 class="inline">Parents Room</h2><a class="button inline" id="Parents-Room-status"></a>
							</header>
							<ul class="actions">
								<li><a href="" class="button ninja-button" data-action="PUT" data-device="Parents Room" data-short-name="On">Parents Room On</a></li>
								<li><a href="" class="button ninja-button" data-action="PUT" data-device="Parents Room" data-short-name="Off">Parents Room Off</a></li>
							</ul>
						</section>
						<section class="12u feature">
							<header>
								<h2 class="inline">Charles Room</h2><a class="button inline" id="Charles-Room-status"></a>
							</header>
							<ul class="actions">
								<li><a href="" class="button ninja-button" data-action="PUT" data-device="Charles Room" data-short-name="On">Charles Room On</a></li>
								<li><a href="" class="button ninja-button" data-action="PUT" data-device="Charles Room" data-short-name="Off">Charles Room Off</a></li>
							</ul>
						</section>
						<section class="12u feature">
							<header>
								<h2>Sensors</h2>
							</header>
							<ul class="actions">
								<li><a class="button">Current Temp</a></li>
								<li><a class="button" id="Temperature-status" data-time-out="1"></a></li>
							</ul>
							<ul class="actions">
								<li><a class="button">Current Humidity</a></li>
								<li><a class="button" id="Humidity-status" data-time-out="1"></a></li>
							</ul>
							<ul class="actions">
								<li><a class="button">Last Button Push</a></li>
								<li><a class="button" id="Remote-status" data-time-out="0" data-to-display="updated_since""></a></li>
							</ul>
						</section>
					</div>
				</div>

			</div>

<?php
}//end if session
?>

				<!-- Copyright -->
					<div id="copyright" class="container">
						<ul class="menu">
							<li>&copy; 2013 Charles Kaïoun. All rights reserved.</li>
						</ul>
					</div>

			</div>


<script>
	initNinja();
	//Init auto get
	getStatus();
</script>
	</body>

</html>
