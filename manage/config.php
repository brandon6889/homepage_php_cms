<?php

if (!defined('IN_ADM')) {
  header('Location: /manage/');
  exit;
}

define('SECRET_NUMBER', 'Fj#*jjfasd*');
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'password1');

session_start();

if (isset($_SESSION['username'])) {
  if (isset($_SESSION['sid']) && $_SESSION['sid'] == md5(sha1(constant('SECRET_NUMBER').constant('ADMIN_PASS').$_SERVER['REMOTE_ADDR']))) {
    define('LOGGED_IN', true);
  } else {
    define('LOGGED_IN', false);
  }
} else {
  define('LOGGED_IN', false);
}

?>
