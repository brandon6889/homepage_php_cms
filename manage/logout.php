<?php

session_start();

unset($_SESSION['username']);
unset($_SESSION['sid']);

header('Location: /manage/');

?>
