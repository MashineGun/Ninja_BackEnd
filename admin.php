<?php

include ($_SERVER['DOCUMENT_ROOT']."/inc/var.php");

if (((isset($_SESSION['login'])) && ($_SESSION['login'] != '')) && ((isset($_SESSION['role'])) && (($_SESSION['role'] == 'admin') || ($_SESSION['role'] == 'demo')))) {


if ((isset($_GET['action'])) && ($_GET['action'] == 'adduser')) {

  if ($_SESSION['role'] == 'admin') {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $password = md5(mysqli_real_escape_string($link, $_POST['password']));
    $sql = "INSERT INTO {$users} (login, password) values ('{$login}', '{$password}')";
    $query = mysqli_query($link, $sql) or die (mysqli_error());
    putInLogs($_SESSION['login'], 'Created user' .$login, '', '', '');
  }
  header("Location: /admin.php");
  exit;

}
else if ((isset($_GET['action'])) && ($_GET['action'] == 'removeuser')) {
  if ($_SESSION['role'] == 'admin') {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    if ($login != 'admin') {
      $sql = "DELETE FROM {$users} WHERE login = '{$login}'";
      $query = mysqli_query($link, $sql) or die (mysqli_error());
      putInLogs($_SESSION['login'], 'Deleted user ' . $login, '', '', '');
    }
  }
  header("Location: /admin.php");
  exit;
}
else if ((isset($_GET['action'])) && ($_GET['action'] == 'edituser')){
  if ($_SESSION['role'] == 'admin') {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $token = mysqli_real_escape_string($link, $_POST['token']);
    $role = mysqli_real_escape_string($link, $_POST['role']);
    if ($_POST['password'] == '') {
      $sql = "UPDATE {$users} SET token = '{$token}', role = '{$role}' WHERE login = '{$login}'";
      putInLogs($_SESSION['login'], 'Updated ' .$login. ' token', '', '', '');
    }
    else {
      $password = md5(mysqli_real_escape_string($link, $_POST['password']));
      $sql = "UPDATE {$users} SET password = '{$password}', token = '{$token}', role = '{$role}' WHERE login = '{$login}'";
      putInLogs($_SESSION['login'], 'Updated ' .$login. ' token & password', '', '', '');
    }
    $query = mysqli_query($link, $sql) or die (mysqli_error());
  }
  header("Location: /admin.php");
  exit;
}
else {

?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>Ninja K Dashboard Admin</title>
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
              </nav>

          </div>

        <!-- Hero -->
          <section id="hero" class="container">
            <header>
              <h2>Ninja K Admin Dashboard</h2>
            </header>
            <ul class="actions">
              <li><a href="#controls" class="button">Take me to the controls</a></li>
            </ul>
          </section>
        </div>

<!-- Controls -->
      <div id="controls" class="wrapper">

        <div class="container">
          <div class="row">
            <section class="12u feature">
              <header>
                <h2>Current Users</h2>
              </header>
              <div class="row">
<?php

if ($_SESSION['role'] == 'admin') {
  $sql = "SELECT id, login, token, role FROM {$users}";
}
else if ($_SESSION['role'] == 'demo'){
  $sql = "SELECT id, login, token, role FROM {$users} WHERE login = '{$_SESSION['login']}'";
}
$query = mysqli_query($link, $sql) or die (mysqli_error());
while ($row = mysqli_fetch_array($query)){
?>
                <section class="6u">
                  <form name="<?php echo $row['login']; ?>-remove-user-form" id="<?php echo $row['login']; ?>-remove-user-form" method="POST" action="?action=removeuser">
                    <div class="row">
                      <div class="6u">
                        <input name="login" type="text" class="text showHide" data-show-hide="<?php echo $row['login']; ?>-edit-user-form" value="<?php echo $row['login']; ?>" readonly="readonly"/>
                      </div>
                    </div>
                    <?php if ($row['login'] != 'admin') { ?>
                    <div class="row half">
                      <div class="6u">
                        <ul class="actions">
                          <li><a href="#" class="button submitForm-button" data-form-id="<?php echo $row['login']; ?>-remove-user-form">Remove</a></li>
                        </ul>
                      </div>
                    </div>
                    <?php } ?>
                  </form>

                  <form class="displayNone" name="<?php echo $row['login']; ?>-edit-user-form" id="<?php echo $row['login']; ?>-edit-user-form" method="POST" action="?action=edituser">
                    <div class="row">
                      <div class="6u">
                        <input name="login" type="hidden" class="text" value="<?php echo $row['login']; ?>"/>
                        <input name="password" type="password" class="text" placeholder="Password"/>
                        <ul class="dropdown noMarginBotton">
                          <li>
                            <input name="role" type="text" class="text label" value="<?php echo $row['role']; ?>"/>
                            <ul>
                              <li><a href="" value="admin">admin</a></li>
                              <li><a href="" value="family">family</a></li>
                              <li><a href="" value="demo">demo</a></li>
                            </ul>
                          </li>
                        </ul>
                        <input name="token" type="text" class="text" value="<?php echo $row['token']; ?>" placeholder="Token"/>
                      </div>
                    </div>
                    <div class="row half">
                      <div class="6u">
                        <ul class="actions">
                          <li><a href="#" class="button submitForm-button" data-form-id="<?php echo $row['login']; ?>-edit-user-form">Change</a></li>
                        </ul>
                      </div>
                    </div>
                  </form>
                </section>

<?php
}
?>
              </div>

            </section>
          </div>
        </div>

        <div class="container">
          <div class="row">
            <section class="12u feature">
              <header>
                <h2>Create User</h2>
              </header>
              <div class="row">

                <section class="12u">
                  <form name="create-user-form" id="create-user-form" method="POST" action="?action=adduser">
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
                          <li><a href="#" class="button submitForm-button" form-id="create-user-form">Submit</a></li>
                        </ul>
                      </div>
                    </div>
                  </form>
                </section>

              </div>

            </section>
          </div>
        </div>

      </div>

      <!-- Get Devices -->
      <div id="promo-wrapper">
        <section id="promo">
          <h2>Get list of available devices (for debugging)</h2>
          <a href="" class="button ninja-button" action="GET" device="All" data-short-name="" target-id="ajax-results">Get devices</a>
        </section>
      </div>

      <!-- Footer -->
      <div id="footer" class="container">
        <header class="major">
          <pre class="pre-scrollable" ><code id="ajax-results"></code></pre>
        </header>
      </div>

        <!-- Copyright -->
          <div id="copyright" class="container">
            <ul class="menu">
              <li>&copy; 2013 Charles Kaïoun. All rights reserved.</li>
            </ul>
          </div>

      </div>


<script>
  initNinja();
</script>
  </body>

</html>

<?php
}//end action if*/

} //end control session
else {
  echo 'You are not an admin';
  header("Location:/");
  exit;
}
?>
