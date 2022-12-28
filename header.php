<html>
  <head>
    <title>Tay's Nail and Spa</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta name="resource-type" content="document" />
    <meta name="distribution" content="global" />
    <meta name="copyright" content="2014 Tay's Nail and Spa" />
    <meta name="keywords" content="Tay nail spa" />
    <meta name="description" content="Tay's Nail and Spa" />
    <meta name="language" content="en"/>
    <meta property="og:type" content="company"/>
    <meta property="og:title" content="Tay's Nail and Spa"/>
    <meta property="og:description" content="Tay's Nail and Spa"/>
    <meta property="og:site_name" content="Tay's Nail and Spa"/>
    <meta property="og:image" content="http://taysnailandspa.com/smlogo.png"/>
    <meta property="og:url" content="http://taysnailandspa.com"/>
    <!--[if (lt IE 9) & (!IEMobile)]>
    <link rel="stylesheet" href="legacy.css" type="text/css" />
    <![endif]-->
    <!--[if (gt IE 8) | (IEMobile) | (!IE)]><!-->
    <link rel="stylesheet" href="/css/tay.css" type="text/css" />
    <!--<![endif]-->
<?php
    if (defined('LOAD_SCRIPTS'))
{
    echo '    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>';
    echo "\n";
    echo '    <script src="galleria/galleria-1.4.2.min.js"></script>';
    echo "\n";
    echo '    <script src="flexslider/jquery.flexslider-min.js"></script>';
    echo "\n";
    echo '    <link rel="stylesheet" href="/flexslider/flexslider.css" type="text/css" />';
    echo "\n";
} ?>
  </head>
  <body>
    <a class="flower_left"></a>
    <!--<a class="flower_right"></a>-->
  <div class="wrapper">
    <a class="flower_right"></a>
    <table class="mainTable">
      <tr><td class="top">
        <div class="top_spacer"></div>
        <div class="top_hours">
<?php
  $topContent = file_get_contents('top.txt');
  $topContent = explode("\n", $topContent);
  echo '          '.array_shift($topContent);
  foreach ($topContent as $line)
    if ($line != '')
      echo "<br />\n          $line\n";
?>
        </div></td>
      </tr>
      <tr><td class="navigation">
        <hr />
        <nav><ul>
          <li><a href="/">Home</a></li>
          <li><a href="/coupons.php">Coupons</a></li>
          <li><a href="/services.php">Services</a></li>
          <li><a href="/gallery.php">Gallery</a></li>
          <li><a href="/about.php">About Us</a></li>
        </ul></nav>
      </td></tr><tr><td class="bottom">
        <div class="content">
