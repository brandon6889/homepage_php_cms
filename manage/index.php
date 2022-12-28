<?php

define('IN_ADM', true);

require ('config.php');

if (LOGGED_IN) {
  header('Location: /manage/services.php');
}

//echo md5(sha1(constant('SECRET_NUMBER').$_POST['password'].$_SERVER['REMOTE_ADDR']));
//echo "\n";
//echo md5(sha1(constant('SECRET_NUMBER').constant('ADMIN_PASS').$_SERVER['REMOTE_ADDR']));

if ($_POST['username'] != '' && $_POST['password'] != '') {
  if ($_POST['username'] == ADMIN_USER && 
      strcmp(md5(sha1(constant('SECRET_NUMBER').$_POST['password'].$_SERVER['REMOTE_ADDR'])),
             md5(sha1(constant('SECRET_NUMBER').constant('ADMIN_PASS').$_SERVER['REMOTE_ADDR']))) == 0) {
    $_SESSION['username'] = ADMIN_USER;
    $_SESSION['sid'] = md5(sha1(constant('SECRET_NUMBER').constant('ADMIN_PASS').$_SERVER['REMOTE_ADDR']));
    header('Location: /manage/services.php');
  } else {
    $error = 'Incorrect Credentials';
  }
}

require ('head.php');

?>

<form id="login" class="login" action="index.php" method="post" accept-charset="UTF-8">
  <fieldset>
    <legend><?php echo (isset($error) ? $error : 'Login'); ?></legend>
    
    <label for="username">Username</label>
    <input type="text" name="username" id="username" maxlength="20" style="margin:10px 0px 20px 0px;" /><br />
    
    <label for="password">Password</label>
    <input type="password" name="password" id="password" maxlength="30" /><br /><br />

    <div style="text-align:center; min-width:100%;">
      <input type="submit" name="submit" value="Submit" />
    </div>
  </fieldset>
</form>
<?php

require ('foot.html');

?>
