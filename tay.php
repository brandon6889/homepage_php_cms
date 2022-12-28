<?php
/*
 * Tay's Nail and Spa
 */

if (!defined('IN_TAYSPA'))
{
        exit;
}

try {
  $db = new PDO("sqlite:tay.sqlite3");
}
catch (PDOException $e) {
  die('Database Unavailable');// nothing
}

?>