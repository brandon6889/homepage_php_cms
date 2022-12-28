<html>
  <head>
    <title>Tay's Spa CMS</title>
    <link rel="stylesheet" href="/manage/manage.css" type="text/css" />
    <script src="/static/jquery.js"></script>
  </head>
  <body>
    <table class="mainTable"><tr>
      <td class="left">
        <img src="/static/logo_white.png" />
<?php
if (LOGGED_IN) { echo '        <ul>
          <li><a href="/manage/services.php">Services</a></li>
          <li><a href="/manage/photos.php">Photos</a></li>
          <li><a href="/manage/coupons.php">Coupons</a></li>
          <li><a href="/manage/contact.php">Contact</a></li>
          <li><a href="/manage/logout.php">Logout</a></li>
        </ul>
';
}
?>
      </td>
      <td class="right"<?php if(!LOGGED_IN) echo ' style="vertical-align:middle;"'; ?>>
