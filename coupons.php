<?php 

if ($_GET['id'] != '') {
  $couponId = intval($_GET['id']);
  if (!file_exists('coupons/'.$couponId.'.txt'))
    header('Location: /coupons.php');

  // load coupon and display content
  $couponData = file_get_contents('coupons/'.$couponId.'.txt');
  $couponFields = explode(']][[', $couponData);

  if (!isset($couponFields[1]) || intval($couponFields[0]) != 0)
    header('Location: /coupons.php');

  require('print.html');

  echo '<h1>'.$couponFields[1].'</h1><p>'.$couponFields[2].'</p><p>'.$couponFields[3].'</p>';
  echo '<a>Expires: '.$couponFields[4].'</a>';
  echo '<img src="/couponCode.php?id='.$couponId.'" />';

  echo '</div>';
  echo '<a class="printButton" href="#" onclick="window.print();return false;">Print coupon</a>';
  echo '</body></html>';


} else {

  require ('header.php');

  echo '          <div class="couponContainer">
';
  
  // fetch coupons from the database, display them
  $coupons = scandir('coupons/');
  unset($coupons[0]);
  unset($coupons[1]);

  foreach ($coupons as $coupon) {
    $couponData = file_get_contents('coupons/'.$coupon);
    $couponFields = explode(']][[', $couponData);
    $couponId = substr($coupon, 0, strpos($coupon, '.'));

    if (isset($couponFields[1]) && intval($couponFields[0]) == 0)
      echo '            <a href="/coupons.php?id='.$couponId.'"><div class="coupon"><div class="coupon_spacer"></div><div class="coupon_content"><h1>'.$couponFields[1].'</h1><p>'.$couponFields[2].'</p></div></div></a>
';
  }
  
  echo '          </div>
          <p style="font-style: italic; text-align: center;">Click a coupon to Print</p>';
  
  if ($_GET['id'] == '') {
    include ('footer.html');
  }
}

?>
